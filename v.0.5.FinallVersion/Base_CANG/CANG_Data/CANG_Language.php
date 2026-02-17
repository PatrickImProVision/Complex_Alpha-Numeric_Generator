<?php
declare(strict_types=1);

if (!class_exists('CANG_Config', false)) {
    require_once __DIR__ . '/CANG_Config.php';
}

final class CANG_Language
{
    /**
     * Build canonical language definitions using CANG_Config symbols/displays.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function Build(): array
    {
        $config = CANG_Config::Build();

        if (!isset($config['Language_Symbol'], $config['Language_Display'])) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        $Language_Symbol = $config['Language_Symbol'];
        $Language_Display = $config['Language_Display'];

        return [
            0 => [
                'Language_Id' => 0,
                'Language_Name' => 'Alphabet_Upper',
                'Language_Type' => $Language_Display['Upper'],
                'Language_Description' => 'Alphabetical -> Simple: Capital Letters',
                'Language_Range' => [$Language_Symbol['Upper']],
            ],
            1 => [
                'Language_Id' => 1,
                'Language_Name' => 'Alphabet_Lower',
                'Language_Type' => $Language_Display['Lower'],
                'Language_Description' => 'Alphabetical -> Simple: Small Letters',
                'Language_Range' => [$Language_Symbol['Lower']],
            ],
            2 => [
                'Language_Id' => 2,
                'Language_Name' => 'Alphabet_Mix',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Lower']],
                'Language_Description' => 'Alphabetical -> Mix: Capital And Small Letters',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Lower']],
            ],
            3 => [
                'Language_Id' => 3,
                'Language_Name' => 'Numeric',
                'Language_Type' => $Language_Display['Numeric'],
                'Language_Description' => 'Numerical -> Simple',
                'Language_Range' => [$Language_Symbol['Numeric']],
            ],
            4 => [
                'Language_Id' => 4,
                'Language_Name' => 'Alphabet_Upper_Num',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Numeric']],
                'Language_Description' => 'Alphabetical And Numerical -> Simple: Capital Letters (Microsoft/Megaupload.com)',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Numeric']],
            ],
            5 => [
                'Language_Id' => 5,
                'Language_Name' => 'Alphabet_Lower_Num',
                'Language_Type' => [$Language_Display['Lower'], $Language_Display['Numeric']],
                'Language_Description' => 'Alphabetical And Numerical -> Simple: Small Letters',
                'Language_Range' => [$Language_Symbol['Lower'], $Language_Symbol['Numeric']],
            ],
            6 => [
                'Language_Id' => 6,
                'Language_Name' => 'Alphabet_Mix_Num',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Lower'], $Language_Display['Numeric']],
                'Language_Description' => 'Alphabetical And Numerical -> Mix: Capital And Small Letters',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Lower'], $Language_Symbol['Numeric']],
            ],
            7 => [
                'Language_Id' => 7,
                'Language_Name' => 'Alphabet_Mix_Num_CharShort',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Lower'], $Language_Display['Numeric'], $Language_Display['CharShort']],
                'Language_Description' => 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Short Special Chars (YouTube.com)',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Lower'], $Language_Symbol['Numeric'], $Language_Symbol['CharShort']],
            ],
            8 => [
                'Language_Id' => 8,
                'Language_Name' => 'Alphabet_Mix_Num_CharLong',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Lower'], $Language_Display['Numeric'], $Language_Display['CharLong']],
                'Language_Description' => 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Long Special Chars (Safe Password)',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Lower'], $Language_Symbol['Numeric'], $Language_Symbol['CharLong']],
            ],
            9 => [
                'Language_Id' => 9,
                'Language_Name' => 'Alphabet_Mix_Num_CharMix',
                'Language_Type' => [$Language_Display['Upper'], $Language_Display['Lower'], $Language_Display['Numeric'], $Language_Display['CharShort'], $Language_Display['CharLong']],
                'Language_Description' => 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Full Special Chars (Safe Password)',
                'Language_Range' => [$Language_Symbol['Upper'], $Language_Symbol['Lower'], $Language_Symbol['Numeric'], $Language_Symbol['CharShort'], $Language_Symbol['CharLong']],
            ],
        ];
    }

    /**
     * Get one language definition by Language_Id.
     *
     * @return array<string, mixed>
     */
    public static function ById(int $Language_Id): array
    {
        $Language = self::Build();
        if (!array_key_exists($Language_Id, $Language)) {
            throw new OutOfBoundsException('ERR_PROFILE_NOT_FOUND');
        }

        $Profile = $Language[$Language_Id];
        $Config = CANG_Config::Build();

        if (
            !isset($Config['Language_Symbol']) || !is_array($Config['Language_Symbol']) ||
            !isset($Config['Language_Display']) || !is_array($Config['Language_Display'])
        ) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        $Symbol_Map = [];
        foreach ($Profile['Language_Range'] as $Group) {
            $Field_Name = self::FindFieldByGroup($Group, $Config['Language_Symbol']);
            $Symbol_Map[$Field_Name] = $Group;
        }

        $Type_List = $Profile['Language_Type'];
        if (!is_array($Type_List)) {
            $Type_List = [$Type_List];
        }

        $Display_Map = [];
        foreach ($Type_List as $Display_Value) {
            $Field_Name = self::FindFieldByDisplay($Display_Value, $Config['Language_Display']);
            $Display_Map[$Field_Name] = $Display_Value;
        }

        $Profile['Language_Symbol'] = $Symbol_Map;
        $Profile['Language_Display'] = $Display_Map;

        return $Profile;
    }

    /**
     * @param array<int, string> $Group
     * @param array<string, array<int, string>> $Language_Symbol
     */
    private static function FindFieldByGroup(array $Group, array $Language_Symbol): string
    {
        foreach ($Language_Symbol as $Field_Name => $Symbol_Group) {
            if ($Group === $Symbol_Group) {
                return $Field_Name;
            }
        }

        throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
    }

    /**
     * @param array<string, string> $Language_Display
     */
    private static function FindFieldByDisplay(string $Display_Value, array $Language_Display): string
    {
        foreach ($Language_Display as $Field_Name => $Configured_Value) {
            if ($Display_Value === $Configured_Value) {
                return $Field_Name;
            }
        }

        throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
    }
}

/*
Example usage:

$Language = CANG_Language::Build();
$Profile = CANG_Language::ById(7);


if (realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $Language = CANG_Language::Build();
    echo json_encode($Language, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
*/
