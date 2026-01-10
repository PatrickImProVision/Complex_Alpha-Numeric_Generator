<?php

class LanguageChar
{
    public static array $Upper        = [];
    public static array $Lower        = [];
    public static array $Numeric      = [];
    public static array $ShortSpecial = [];
    public static array $FullSpecial  = [];

    public static function init(): void
    {
        self::$Upper        = range('A', 'Z');
        self::$Lower        = range('a', 'z');
        self::$Numeric      = range('0', '9');
        self::$ShortSpecial = str_split('_-');
        self::$FullSpecial  = str_split('#$%&+-@_');
    }
}

// Initialize the character sets
LanguageChar::init();

// Example usage:
// print_r(LanguageChar::$Upper);
// print_r(LanguageChar::$FullSpecial);

?>