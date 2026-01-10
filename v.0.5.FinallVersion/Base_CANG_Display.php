<?php

// Insert This To Include LanguageChar Configuration:
$CANG_LanguageChar = require __DIR__ . '/Base_CANG_Config/CANG_LanguageCharFoundation.php';

// Insert This To Include Language Class:
require __DIR__ . '/Base_CANG_Language/CANG_LanguageClass.php';

// Inssert This To Include CANG Class:
// require __DIR__ . '/Base_CANG_Generators/CANG.php';

// Then Load The LanguageDefinition:
$CANG_LanguageDefinition = CANG_LanguageDefinition::BuildAll($CANG_LanguageChar['LanguageChar']);

// You Can Print The OutPut And Continue To Configure Your FrameWork:
print_r($CANG_LanguageDefinition);

class CANG_Language {

    protected array $Definition;
    protected int $Id;
    protected string $Name;
    protected array $OutPut;

    public function __construct(array $LanguageAll){
        
        $this->Definition = $LanguageAll;

    }

    public function Input(array $Input){
        $this->Definition = $Input;
    }

    public function SelectId(int $Id){
        
        foreach(range(0,Count($this->Definition)) As $LanguageId){
            
            if($LanguageId == $Id){
                $this->Id = $Id;
                $this->OutPut = $this->Definition[$Id];
            }
        }
    }

    public function SelectName(string $Name){
        // Find name in definition and define output
        foreach($this->Definition As $LanguageTable){
            
            if($LanguageTable['Name'] == $Name){
                $this->Name = $Name;
                $this->OutPut = $LanguageTable;
            }
        }
    }

    public function GetOutPut(){
        return $this->OutPut;
    }

}

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