<?php

/**
 * Fundamental Language Characters
 */
$LanguageChar = [
    'Upper'        => range('A', 'Z'),
    'Lower'        => range('a', 'z'),
    'Numeric'      => range('0', '9'),
    'ShortSpecial' => str_split('_-'),
    'FullSpecial'  => str_split('#$%&+-@_')
];

/**
 * Basic Language Names
 */
$LanguageName = [
    1 => 'Alphabet_Upper',
    2 => 'Alphabet_Lower',
    3 => 'Alphabet_Mix',
    4 => 'Numeric',
    5 => 'Alphabet_Upper_Num',
    6 => 'Alphabet_Lower_Num',
    7 => 'Alphabet_Mix_Num',
    8 => 'Alphabet_Mix_Num_SpecialShort',
    9 => 'Alphabet_Mix_Num_SpecialFull'
];

/**
 * Basic Language Types
 */
$LanguageType = [
    1 => '[A-Z]',
    2 => '[a-z]',
    3 => '[A-Z,a-z]',
    4 => '[0-9]',
    5 => '[A-Z,0-9]',
    6 => '[a-z,0-9]',
    7 => '[A-Z,a-z,0-9]',
    8 => '[A-Z,a-z,0-9,-_]',
    9 => '[A-Z,a-z,0-9,-_]'
];

/**
 * Basic Language Descriptions
 */
$LanguageDescription = [
    1 => 'Alphabetical -> Simple: Capital letters',
    2 => 'Alphabetical -> Simple: Small letters',
    3 => 'Alphabetical -> Mix: Capital and Small letters',
    4 => 'Numerical -> Simple',
    5 => 'Alphabetical And Numerical -> Simple: Capital letters (Megaupload.com)',
    6 => 'Alphabetical and Numerical -> Simple: Small letters',
    7 => 'Alphabetical and Numerical -> Mix: Capital and Small letters',
    8 => 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Short Special chars (YouTube.com)',
    9 => 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Full Special chars (Safe Password)'
];

/**
 * Basic Language Ranges
 */
$LanguageRange = [
    1 => [$LanguageChar['Upper']],
    2 => [$LanguageChar['Lower']],
    3 => [$LanguageChar['Upper'], $LanguageChar['Lower']],
    4 => [$LanguageChar['Numeric']],
    5 => [$LanguageChar['Upper'], $LanguageChar['Numeric']],
    6 => [$LanguageChar['Lower'], $LanguageChar['Numeric']],
    7 => [$LanguageChar['Upper'], $LanguageChar['Lower'], $LanguageChar['Numeric']],
    8 => [$LanguageChar['Upper'], $LanguageChar['Lower'], $LanguageChar['Numeric'], $LanguageChar['ShortSpecial']],
    9 => [$LanguageChar['Upper'], $LanguageChar['Lower'], $LanguageChar['Numeric'], $LanguageChar['FullSpecial']]
];

/**
 * Language Definition (Ordered)
 */
$LanguageDefinition = [];

foreach (range(1, 9) as $id) {
    $LanguageDefinition[$id] = [
        'LanguageId'          => $id,
        'LanguageName'        => $LanguageName[$id],
        'LanguageType'        => $LanguageType[$id],
        'LanguageDescription' => $LanguageDescription[$id],
        'LanguageRange'       => $LanguageRange[$id],
    ];
}
?>