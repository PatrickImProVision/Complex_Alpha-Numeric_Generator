<?php

class CANG_LanguageDefinition
{
    protected int $id;
    protected string $name;
    protected string $type;
    protected string $description;
    protected array $range;

    public function __construct(
        int $id,
        string $name,
        string $type,
        string $description,
        array $range
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->type        = $type;
        $this->description = $description;
        $this->range       = $range;
    }

    // -----------------------------
    // Accessors
    // -----------------------------

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRange(): array
    {
        return $this->range;
    }

    // -----------------------------
    // Utility
    // -----------------------------

    public function getFlattenedRange(): array
    {
        return array_merge(...$this->range);
    }

    public function containsChar(string $char): bool
    {
        return in_array($char, $this->getFlattenedRange(), true);
    }

    // -----------------------------
    // Factory: Build All Definitions
    // -----------------------------

    public static function BuildAll(array $LanguageChar): array
    {
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

        $definitions = [];

        foreach (range(1, 9) As $id) {
            $definitions[$id] = New Self(
                $id,
                $LanguageName[$id],
                $LanguageType[$id],
                $LanguageDescription[$id],
                $LanguageRange[$id]
            );
        }

        return $definitions;
    }
}

/*
Example: How To Use

Insert This To Include LanguageChar Configuration:
$CANG_LanguageCharFoundation = require __DIR__ . '/CANG_Config/CANG_LanguageCharFoundation.php';

Then Load The LanguageDefinition:
$CANG_LanguageDefinition = CANG_LanguageDefinition::BuildAll($CANG_LanguageCharFoundation['LanguageChar']);

You Can Print The OutPut And Continue To Configure Your FrameWork:
print_r($CANG_LanguageDefinition);

*/
?>