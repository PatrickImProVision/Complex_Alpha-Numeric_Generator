<?php

// Define Class Language Selector

class CANG_LanguageSelect
{
    protected array $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    // ---------------------------------------------------------
    // Selectors
    // ---------------------------------------------------------

    public function byId(int $id): ?CANG_LanguageProfile
    {
        return $this->definitions[$id] ?? null;
    }

    public function byName(string $name): ?CANG_LanguageProfile
    {
        foreach ($this->definitions as $definition) {
            if ($definition->getName() === $name) {
                return $definition;
            }
        }
        return null;
    }

    // ---------------------------------------------------------
    // Output Formatter
    // ---------------------------------------------------------

    public function output(CANG_LanguageProfile $definition): array
    {
        return [
            'id'          => $definition->getId(),
            'name'        => $definition->getName(),
            'type'        => $definition->getType(),
            'description' => $definition->getDescription(),
            'range'       => $definition->getRange(),
            'flattened'   => $definition->getFlattenedRange(),
        ];
    }
}

// Insert This To Include LanguageChar Configuration:
$CANG_LanguageChar = require __DIR__ . '/Base_CANG_Config/CANG_LanguageChar.php';

// Insert This To Include Language Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageProfile.php';

// Inssert This To Include CANG Class:
// require __DIR__ . '/Base_CANG_Generators/CANG.php';

// Then Load The LanguageDefinition:
$CANG_LanguageProfile = CANG_LanguageProfile::BuildAll($CANG_LanguageChar['LanguageChar']);

// You Can Print The OutPut And Continue To Configure Your FrameWork:
//print_r($CANG_LanguageProfile);

$CANG_LanguageSelect = New CANG_LanguageSelect($CANG_LanguageProfile);
$CANG_LanguageRange = $CANG_LanguageSelect->byName('Alphabet_Upper');
$CANG_LanguageOutPut = $CANG_LanguageSelect->output($CANG_LanguageRange);
print_r($CANG_LanguageOutPut);

/*
// Insert This To Run CANG:
$CANG = New CANG($CANG_LanguageDefinition, 8);

echo $CANG->generate(CANG::MODE_BEGINNING); // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "0");   // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "1");   // AAAAAAAB
echo $CANG->generate(CANG::MODE_ID, "2");   // AAAAAAAC
echo $CANG->generate(CANG::MODE_NEXT, "AAAAAAAB"); // AAAAAAAC
echo $CANG->stringToPosition("AAAAAAAC");          // 2
*/
?>