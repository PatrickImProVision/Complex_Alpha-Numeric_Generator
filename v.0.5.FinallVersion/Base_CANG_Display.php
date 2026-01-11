<?php

// Insert This To Include LanguageChar Configuration:
$CANG_LanguageChar = require __DIR__ . '/Base_CANG_Config/CANG_LanguageChar.php';

// Insert This To Include Language Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageProfile.php';

// Then Load The LanguageDefinition:
$CANG_LanguageProfile = CANG_LanguageProfile::BuildAll($CANG_LanguageChar['LanguageChar']);

// You Can Print The OutPut And Continue To Configure Your FrameWork:
//print_r($CANG_LanguageProfile);

// Insert This To Include CANG_LanguageSelect Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageSelect.php';

/*

$CANG_LanguageSelect = new \CANG_LanguageSelect($CANG_LanguageProfile)
$CANG_LanguageRange = $CANG_LanguageSelect->byId(7);
print_r($Select->output($CANG_LanguageRange));

$CANG_LanguageSelect = new \CANG_LanguageSelect($CANG_LanguageProfile)
$CANG_LanguageRange = $CANG_LanguageSelect->byName('Alphabet_Mix_Num');
print_r($Select->output($CANG_LanguageRange));


// Inssert This To Include CANG Class:
// require __DIR__ . '/Base_CANG_Generators/CANG.php';

// Insert This To Run CANG:
$CANG = New CANG($CANG_LanguageRange, 8);

echo $CANG->generate(CANG::MODE_BEGINNING); // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "0");   // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "1");   // AAAAAAAB
echo $CANG->generate(CANG::MODE_ID, "2");   // AAAAAAAC
echo $CANG->generate(CANG::MODE_NEXT, "AAAAAAAB"); // AAAAAAAC
echo $CANG->stringToPosition("AAAAAAAC");          // 2
*/
?>