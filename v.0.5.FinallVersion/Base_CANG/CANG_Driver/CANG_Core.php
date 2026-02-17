<?php
declare(strict_types=1);

final class CANG_Core
{
    /** @var array<int, string> */
    private static array $Symbol_List = [];
    /** @var array<string, int> */
    private static array $Symbol_Index = [];
    private static bool $Is_Configured = false;

    /**
     * Configure core runtime with pure symbol data.
     *
     * @param array<int, string> $Symbol_List
     * @param array<string, int> $Symbol_Index
     */
    public static function Setup(array $Symbol_List, array $Symbol_Index): void
    {
        self::$Symbol_List = array_values($Symbol_List);
        self::$Symbol_Index = $Symbol_Index;
        self::$Is_Configured = true;
    }

    /**
     * Convert a code string into its numeric id using base-N positional math.
     * - Each character is looked up in Symbol_Index.
     * - Left-to-right fold: Id = Id * Base + Digit.
     * - Throws if any character is not in the configured alphabet.
     */
    public static function ConvertTo_Id(string $Code): int
    {
        self::EnsureReady();

        $Base = count(self::$Symbol_List);
        $Id = 0;
        $Length = strlen($Code);

        for ($I = 0; $I < $Length; $I++) {
            $Char = $Code[$I];
            $Digit = self::$Symbol_Index[$Char] ?? null;
            if ($Digit === null) {
                throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
            }
            if ($Id > intdiv(PHP_INT_MAX - $Digit, $Base)) {
                return PHP_INT_MAX;
            }
            $Id = ($Id * $Base) + $Digit;
        }

        return $Id;
    }

    /**
     * Convert a numeric id into a fixed-length code string.
     * - Repeatedly divides by Base to get digits from right to left.
     * - Left-pads with first alphabet symbol when value has fewer digits.
     * - Throws when Number cannot fit in the requested Length.
     */
    public static function ConvertTo_Code(int $Number, int $Length): string
    {
        self::EnsureReady();
        self::EnsureLength($Length);

        if ($Number < 0) {
            throw new InvalidArgumentException('ERR_NUMBER_REQUIRED');
        }

        $Base = count(self::$Symbol_List);
        $Buffer = array_fill(0, $Length, self::$Symbol_List[0]);
        $Work = $Number;

        for ($I = $Length - 1; $I >= 0; $I--) {
            $Digit = $Work % $Base;
            $Buffer[$I] = self::$Symbol_List[$Digit];
            $Work = intdiv($Work, $Base);
        }

        if ($Work > 0) {
            throw new InvalidArgumentException('ERR_NUMBER_OUT_OF_RANGE');
        }

        return implode('', $Buffer);
    }

    /**
     * Return the first sequence value for the selected alphabet and length.
     * Equivalent to Id(0, Length).
     *
     * @return array<string, int|string>
     */
    public static function Beginy(int $Length): array
    {
        return self::Id(0, $Length);
    }

    /**
     * Return the previous sequence value for a given code.
     * - Resolves current Id first.
     * - Subtracts 1 and returns the converted pair.
     * - Throws when already at sequence start.
     *
     * @return array<string, int|string>
     */
    public static function Previous(string $Code, int $Length): array
    {
        $Current = self::Current($Code, $Length);
        $Prev_Id = (int) $Current['Id'] - 1;
        if ($Prev_Id < 0) {
            throw new InvalidArgumentException('ERR_SEQUENCE_START');
        }

        return self::Id($Prev_Id, $Length);
    }

    /**
     * Return the current pair for input code.
     * - Converts Code -> Id.
     * - Returns both values in unified shape.
     *
     * @return array<string, int|string>
     */
    public static function Current(string $Code, int $Length): array
    {
        self::EnsureLength($Length);

        $Id = self::ConvertTo_Id($Code);

        return [
            'Code' => $Code,
            'Id' => $Id,
        ];
    }

    /**
     * Return the next sequence value for a given code.
     * - Resolves current Id first.
     * - Adds 1 and returns the converted pair.
     * - Throws when already at sequence end.
     *
     * @return array<string, int|string>
     */
    public static function Next(string $Code, int $Length): array
    {
        $Current = self::Current($Code, $Length);
        $Next_Id = (int) $Current['Id'] + 1;
        if ($Next_Id > self::GetMaxNumber($Length)) {
            throw new InvalidArgumentException('ERR_SEQUENCE_END');
        }

        return self::Id($Next_Id, $Length);
    }

    /**
     * Return the last possible sequence value for the given length.
     *
     * @return array<string, int|string>
     */
    public static function End(int $Length): array
    {
        return self::Id(self::GetMaxNumber($Length), $Length);
    }

    /**
     * Return one random valid sequence value for the given length.
     * - Builds random code from configured symbols.
     * - Resolves and returns unified current pair.
     *
     * @return array<string, int|string>
     */
    public static function Random(int $Length): array
    {
        self::EnsureReady();
        self::EnsureLength($Length);

        $Max = count(self::$Symbol_List) - 1;
        $Code = '';
        for ($I = 0; $I < $Length; $I++) {
            $Code .= self::$Symbol_List[random_int(0, $Max)];
        }

        return self::Current($Code, $Length);
    }

    /**
     * Return sequence pair from explicit numeric id and length.
     *
     * @return array<string, int|string>
     */
    public static function Id(int $Number, int $Length): array
    {
        $Code = self::ConvertTo_Code($Number, $Length);
        return self::Current($Code, $Length);
    }

    /**
     * Guard that core runtime is configured with a usable symbol set.
     */
    private static function EnsureReady(): void
    {
        if (!self::$Is_Configured || count(self::$Symbol_List) < 2) {
            throw new InvalidArgumentException('ERR_CORE_NOT_CONFIGURED');
        }
    }

    /**
     * Guard that requested length is valid for generation/conversion.
     */
    private static function EnsureLength(int $Length): void
    {
        if ($Length < 1) {
            throw new InvalidArgumentException('ERR_LENGTH_REQUIRED');
        }
    }

    /**
     * Calculate maximum id for current Base and requested Length:
     * Max = (Base^Length) - 1
     */
    private static function GetMaxNumber(int $Length): int
    {
        self::EnsureReady();
        self::EnsureLength($Length);

        $Base = count(self::$Symbol_List);
        $Max = 1;
        for ($I = 0; $I < $Length; $I++) {
            $Max *= $Base;
        }

        return $Max - 1;
    }
}

/*
Runnable Example: CANG_Core Sequence Demo
Description:
- Configures `CANG_Core` with a small symbol set.
- Demonstrates each sequence mode in practical order.
- Prints one JSON object so developer can inspect each result.

Step 0) Configure core once with Symbol list and Index map:
$Symbol_List = ['A', 'B', '0', '1', '_'];
$Symbol_Index = [
    'A' => 0,
    'B' => 1,
    '0' => 2,
    '1' => 3,
    '_' => 4,
];
// CANG_Core::Setup($Symbol_List, $Symbol_Index);

Step 1) Beginy(Length): first code of selected alphabet/length.
$Beginy = CANG_Core::Beginy(4);                        // First

Step 2) Next(Code, Length): move forward from current code.
$Next_1 = CANG_Core::Next($Beginy['Code'], 4);         // Second
$Next_2 = CANG_Core::Next($Next_1['Code'], 4);         // Third

Step 3) Previous(Code, Length): move backward from current code.
$Previous = CANG_Core::Previous($Next_2['Code'], 4);   // Back to second

Step 4) Current(Code, Length): read code + id for any current code.
$Current = CANG_Core::Current($Next_1['Code'], 4);

Step 5) End(Length): last possible code for selected alphabet/length.
$End = CANG_Core::End(4);

Step 6) Random(Length): random valid code.
$Random = CANG_Core::Random(4);

Step 7) Id(Number, Length): build code from explicit id.
$From_Id = CANG_Core::Id(17, 4);
*/

/*
// Run this demo only when executing this file directly.
if (realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $Symbol_List = ['A', 'B', '0', '1', '_'];
    $Symbol_Index = [
        'A' => 0,
        'B' => 1,
        '0' => 2,
        '1' => 3,
        '_' => 4,
    ];

    CANG_Core::Setup($Symbol_List, $Symbol_Index);

    // 1) Beginy: generate first sequence pair from Id=0.
    $Beginy = CANG_Core::Beginy(4);

    // 2) Next: move one step forward from Beginy.
    $Next_1 = CANG_Core::Next((string) $Beginy['Code'], 4);
    // 2b) Next again: move one more step forward.
    $Next_2 = CANG_Core::Next((string) $Next_1['Code'], 4);

    // 3) Previous: move one step backward from Next_2.
    $Previous = CANG_Core::Previous((string) $Next_2['Code'], 4);

    // 4) Current: resolve exact Code <-> Id pair for an existing code.
    $Current = CANG_Core::Current((string) $Next_1['Code'], 4);

    // 5) End: generate final sequence pair for the same Length.
    $End = CANG_Core::End(4);

    // 6) Random: generate one random valid pair.
    $Random = CANG_Core::Random(4);

    // 7) Id: generate pair from explicit number.
    $From_Id = CANG_Core::Id(17, 4);

    $Runnable_Example_Output = [
        'How_To_Read' => [
            'Beginy' => 'First sequence pair (Id=0).',
            'Next_1' => 'One step after Beginy.',
            'Next_2' => 'Two steps after Beginy.',
            'Previous_From_Next_2' => 'Back one step from Next_2.',
            'Current' => 'Resolve current Code -> Id.',
            'End' => 'Last sequence pair for Length.',
            'Random' => 'Random valid pair for Length.',
            'Id_17' => 'Pair generated from Number=17.',
        ],
        'Sequence' => [
            'Beginy' => $Beginy,
            'Next_1' => $Next_1,
            'Next_2' => $Next_2,
            'Previous_From_Next_2' => $Previous,
        ],
        'Current' => $Current,
        'End' => $End,
        'Random' => $Random,
        'Id_17' => $From_Id,
    ];

    echo json_encode($Runnable_Example_Output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
*/
