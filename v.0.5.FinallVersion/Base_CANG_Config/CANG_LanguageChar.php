<?php

/**
 * Fundamental Language Character Configuration
 *
 * This file defines the core character sets used across the system.
 * Each set is intentionally explicit, immutable, and easily composable.
 */

return [

    // Alphabetic Characters
    'LanguageChar' => [

        // A–Z
        'Upper' => range('A', 'Z'),

        // a–z
        'Lower' => range('a', 'z'),

        // 0–9
        'Numeric' => range('0', '9'),

        // Minimal special characters
        'ShortSpecial' => str_split('_-'),

        // Full special character set
        'FullSpecial' => str_split('#$%&+-@_'),
    ],

];

/*
Example: How To Use

$LanguageCharFoundation = require __DIR__ . '/CANG_Config/CANG_LanguageChar.php';

Then Insert This Code Into Process:
$LanguageChar['LanguageChar']
It Will Help You To Match The Configuration InSide The Array.
*/

?>