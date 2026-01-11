<?php
include('../Base_CANG_Generators/CANG.php');
$CANG = new CANG;
$CANG->SetLength(8);
$CANG->SetType(5);
$CANG->CodeInput();
$return[1] = $CANG->Generate_String();
$CANG->CodeInput($return[1]['code_base']);
$return[] = $CANG->Generate_String();
print_r($return);
?>
