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
?>