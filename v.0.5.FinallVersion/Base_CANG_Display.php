<?php

// Insert This To Include LanguageChar Configuration:
$CANG_LanguageChar = require __DIR__ . '/Base_CANG_Config/CANG_LanguageChar.php';

// Insert This To Include Language Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageProfile.php';

// Then Load The LanguageDefinition:
$CANG_LanguageProfile = CANG_LanguageProfile::BuildAll($CANG_LanguageChar['LanguageChar']);

// You Can Print The OutPut And Continue To Configure Your FrameWork:
//print_r($CANG_LanguageProfile);

require __DIR__ . '/Base_CANG_Language/CANG_LanguageSelect.php';

$CANG_LanguageSelect = New CANG_LanguageSelect($CANG_LanguageProfile);
$CANG_LanguageRange = $CANG_LanguageSelect->byName('Alphabet_Upper');
$CANG_LanguageOutPut = $CANG_LanguageSelect->output($CANG_LanguageRange);
print_r($CANG_LanguageOutPut);

/*

// Inssert This To Include CANG Class:
// require __DIR__ . '/Base_CANG_Generators/CANG.php';

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