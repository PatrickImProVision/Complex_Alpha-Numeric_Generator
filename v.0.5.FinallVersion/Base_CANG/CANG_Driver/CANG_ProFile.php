<?php
declare(strict_types=1);

if (!class_exists('CANG_Language', false)) {
    require_once __DIR__ . '/../CANG_Data/CANG_Language.php';
}

final class CANG_ProFile
{
    private const MAX_BASE_FIELDS = 5;
    private static ?int $Language_Id = null;
    private static ?array $Preview_Profile = null;
    private static ?array $Selected_Profile = null;

    /**
     * Load base language configuration set.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function LoadConfig(): array
    {
        return CANG_Language::Build();
    }

    public static function SetId(int $Language_Id): void
    {
        if ($Language_Id < 0) {
            throw new InvalidArgumentException('ERR_PROFILE_NOT_FOUND');
        }

        self::$Language_Id = $Language_Id;
        self::$Preview_Profile = null;
        self::$Selected_Profile = null;
    }

    /**
     * Expose selected profile field names (array keys).
     *
     * @return array<int, string>
     */
    public static function ExposeValueArrayNames(): array
    {
        if (self::$Language_Id === null) {
            throw new InvalidArgumentException('ERR_PROFILE_NOT_FOUND');
        }

        $Profile = CANG_Language::ById(self::$Language_Id);
        return array_keys($Profile);
    }

    /**
     * Build all possible order profiles for Language_Range and Language_Type together.
     *
     * If one field exists -> fixed order.
     * If multiple fields exist -> all permutations.
     *
     * @return array<string, mixed>
     */
    public static function BuildOrderProfiles(): array
    {
        if (self::$Language_Id === null) {
            throw new InvalidArgumentException('ERR_PROFILE_NOT_FOUND');
        }

        $Profile = CANG_Language::ById(self::$Language_Id);
        if (
            !isset($Profile['Language_Symbol']) || !is_array($Profile['Language_Symbol']) ||
            !isset($Profile['Language_Display']) || !is_array($Profile['Language_Display'])
        ) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        $Language_Symbol = $Profile['Language_Symbol'];
        $Language_Display = $Profile['Language_Display'];

        $Field_Names = array_values(
            array_intersect(array_keys($Language_Symbol), array_keys($Language_Display))
        );

        if (count($Field_Names) === 0) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }
        if (count($Field_Names) > self::MAX_BASE_FIELDS) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        $Orders = [];
        if (count($Field_Names) === 1) {
            $Order_Field_List = $Field_Names;
            $Join = self::BuildJoinedRangeAndIndex([$Language_Symbol[$Order_Field_List[0]]]);
            $Orders['Order_1'] = [
                'Field_Order' => $Order_Field_List,
                'Language_Range' => [$Language_Symbol[$Order_Field_List[0]]],
                'Language_Type' => [$Language_Display[$Order_Field_List[0]]],
                'Language_Symbol_Build' => $Join['Language_Symbol_Build'],
                'Language_Symbol_Index' => $Join['Language_Symbol_Index'],
            ];

            self::$Preview_Profile = [
                'Language_Id' => self::$Language_Id,
                'Order_Mode' => 'Fixed',
                'Order_Total' => 1,
                'Orders' => $Orders,
            ];
            self::$Selected_Profile = null;

            return self::$Preview_Profile;
        }

        $Permutations = self::BuildSequentialFieldOrders($Field_Names);
        $Order_Index = 1;

        foreach ($Permutations as $Field_Order) {
            $Ordered_Range = [];
            $Ordered_Type = [];

            foreach ($Field_Order as $Field_Name) {
                $Ordered_Range[] = $Language_Symbol[$Field_Name];
                $Ordered_Type[] = $Language_Display[$Field_Name];
            }
            $Join = self::BuildJoinedRangeAndIndex($Ordered_Range);

            $Orders['Order_' . $Order_Index] = [
                'Field_Order' => $Field_Order,
                'Language_Range' => $Ordered_Range,
                'Language_Type' => $Ordered_Type,
                'Language_Symbol_Build' => $Join['Language_Symbol_Build'],
                'Language_Symbol_Index' => $Join['Language_Symbol_Index'],
            ];
            $Order_Index++;
        }

        self::$Preview_Profile = [
            'Language_Id' => self::$Language_Id,
            'Order_Mode' => 'Permutations',
            'Order_Total' => count($Orders),
            'Orders' => $Orders,
        ];
        self::$Selected_Profile = null;

        return self::$Preview_Profile;
    }

    /**
     * Select one order from preview and return selected profile output.
     *
     * @return array<string, mixed>
     */
    public static function SelectOrder(string $Order_Key): array
    {
        if (self::$Preview_Profile === null) {
            throw new InvalidArgumentException('ERR_PROFILE_PREVIEW_REQUIRED');
        }

        $Profiles = self::$Preview_Profile;
        if (!isset($Profiles['Orders']) || !is_array($Profiles['Orders'])) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        $Orders = $Profiles['Orders'];
        if (!array_key_exists($Order_Key, $Orders)) {
            throw new InvalidArgumentException('ERR_PROFILE_NOT_FOUND');
        }

        $Selected = $Orders[$Order_Key];
        $Language_Profile = CANG_Language::ById((int) $Profiles['Language_Id']);

        self::$Selected_Profile = [
            'Language_Id' => $Profiles['Language_Id'],
            'Order_Mode' => $Profiles['Order_Mode'],
            'Selected_Order' => $Order_Key,
            'Selected_Data' => $Selected,
        ];
        self::$Preview_Profile = null;

        return [
            'Meta' => [
                'Language_Id' => self::$Selected_Profile['Language_Id'],
                'Language_Name' => (string) ($Language_Profile['Language_Name'] ?? ''),
                'Language_Description' => (string) ($Language_Profile['Language_Description'] ?? ''),
                'Selected_Order' => self::$Selected_Profile['Selected_Order'],
                'Preview_Count' => $Profiles['Order_Total'],
                'Available_Order_Id' => array_keys($Orders),
            ],
            'Profile' => [
                'Order' => $Selected['Field_Order'],
                'Symbol' => $Selected['Language_Symbol_Build'],
                'Index' => $Selected['Language_Symbol_Index'],
            ],
        ];
    }

    /**
     * @param array<int, string> $Fields
     * @return array<int, array<int, string>>
     */
    private static function BuildSequentialFieldOrders(array $Fields): array
    {
        $Count = count($Fields);
        if ($Count <= 1) {
            return [$Fields];
        }

        $Permutations = [];
        $Indices = range(0, $Count - 1);

        while (true) {
            $Order = [];
            foreach ($Indices as $Index) {
                $Order[] = $Fields[$Index];
            }
            $Permutations[] = $Order;

            if (!self::NextPermutation($Indices)) {
                break;
            }
        }

        return $Permutations;
    }

    /**
     * Advance integer sequence to next lexicographic permutation.
     *
     * @param array<int, int> $Values
     */
    private static function NextPermutation(array &$Values): bool
    {
        $N = count($Values);
        $I = $N - 2;
        while ($I >= 0 && $Values[$I] >= $Values[$I + 1]) {
            $I--;
        }
        if ($I < 0) {
            return false;
        }

        $J = $N - 1;
        while ($Values[$J] <= $Values[$I]) {
            $J--;
        }

        [$Values[$I], $Values[$J]] = [$Values[$J], $Values[$I]];

        $Left = $I + 1;
        $Right = $N - 1;
        while ($Left < $Right) {
            [$Values[$Left], $Values[$Right]] = [$Values[$Right], $Values[$Left]];
            $Left++;
            $Right--;
        }

        return true;
    }

    /**
     * Join ordered Language_Range groups into one unique array and build index map.
     *
     * @param array<int, array<int, string>> $Ordered_Range
     * @return array<string, array>
     */
    private static function BuildJoinedRangeAndIndex(array $Ordered_Range): array
    {
        $Joined = [];
        $Seen = [];

        foreach ($Ordered_Range as $Group) {
            foreach ($Group as $Value) {
                if (!isset($Seen[$Value])) {
                    $Seen[$Value] = true;
                    $Joined[] = $Value;
                }
            }
        }

        $Index = [];
        foreach ($Joined as $Position => $Value) {
            $Index[$Value] = $Position;
        }

        return [
            'Language_Symbol_Build' => $Joined,
            'Language_Symbol_Index' => $Index,
        ];
    }
}

/*
Complete example:

1) Select profile id
2) Build preview orders
3) Select one order key
4) Get selected output

CANG_ProFile::SetId(7);
$Preview_Profiles = CANG_ProFile::BuildOrderProfiles();
$Order_Key = 'Order_2'; //<- Here Select Number According To Maximum Allowed Id
if (!isset($Preview_Profiles['Orders'][$Order_Key])) {
    $Order_Key = 'Order_1'; //<- Only FallBack If Key Not Exists
}
$Selected_Output = CANG_ProFile::SelectOrder($Order_Key);


if (realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $Selected_Id = 9;
    CANG_ProFile::SetId($Selected_Id);

    // Step 1: Preview possible orders.
    $Preview_Profiles = CANG_ProFile::BuildOrderProfiles();
    //print_r($Preview_Profiles);
    $Order_Key = 'Order_49';
    if (!isset($Preview_Profiles['Orders'][$Order_Key])) {
        $Order_Key = 'Order_1';
    }

    // Step 2: Select one order. Preview state is cleared after this call.
    $Selected_Output = CANG_ProFile::SelectOrder($Order_Key);

    echo json_encode([
        'Selected_Output' => $Selected_Output,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

}
*/
