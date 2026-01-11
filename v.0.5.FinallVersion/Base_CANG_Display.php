<?php

// The Complete Configuration Is Divided InTo: Four Settings As Follows
// Base_CANG_Config, Base_CANG_Language, Base_CANG_Generators, Base_CANG_NoMySQL_Examples


// Insert This To Include CANG_LanguageChar Configuration:
$CANG_LanguageChar = require __DIR__ . '/Base_CANG_Config/CANG_LanguageChar.php';

// Insert This To Include CANG_LanguageProfile Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageProfile.php';

// Insert This To Include CANG_LanguageSelect Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageSelect.php';

// Inssert This To Include CANG Class:
require __DIR__ . '/Base_CANG_Generators/CANG.php';

// Then Load The LanguageDefinition:
$CANG_LanguageProfile = CANG_LanguageProfile::BuildAll($CANG_LanguageChar['LanguageChar']);

// You Can Print The OutPut And Continue To Configure Your FrameWork:
//print_r($CANG_LanguageProfile);

/*
// Initialize Class Selector
$CANG_LanguageSelect = new \CANG_LanguageSelect($CANG_LanguageProfile)
// Select By Id
$CANG_LanguageRange = $CANG_LanguageSelect->byId(7);
// Or Select By Name
$CANG_LanguageRange = $CANG_LanguageSelect->byName('Alphabet_Mix_Num');
// OutPut Selection
print_r($Select->output($CANG_LanguageRange));

// Insert This To Run CANG:
$CANG = New CANG($CANG_LanguageRange, 8);

echo $CANG->generate(CANG::MODE_BEGINNING); // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "0");   // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "1");   // AAAAAAAB
echo $CANG->generate(CANG::MODE_ID, "2");   // AAAAAAAC
echo $CANG->generate(CANG::MODE_NEXT, "AAAAAAAB"); // AAAAAAAC
echo $CANG->stringToPosition("AAAAAAAC");          // 2
*/
