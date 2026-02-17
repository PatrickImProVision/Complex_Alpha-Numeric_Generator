<?php
declare(strict_types=1);

final class CANG_Config
{
    /**
     * Build the base configuration map for language symbols and display values.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function Build(): array
    {
        return [
            'Language_Symbol' => [
                'Upper' => range('A', 'Z'),
                'Lower' => range('a', 'z'),
                'Numeric' => range('0', '9'),
                'CharShort' => str_split('_-'),
                'CharLong' => str_split('#$%&+-@'),
            ],
            'Language_Display' => [
                'Upper' => '[A-Z]',
                'Lower' => '[a-z]',
                'Numeric' => '[0-9]',
                'CharShort' => '[-_]',
                'CharLong' => '[#$%&+-@]',
            ],
        ];
    }
}

/*
Example usage:

$LanguageConfig = CANG_Config::Build();
$LanguageSymbol = $Config['Language_Symbol'];
$LanguageDisplay = $Config['Language_Display'];


// Quick run example when this file is executed directly.
if (realpath((string)($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $LanguageConfig = CANG_Config::Build();
    echo json_encode($LanguageConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
*/