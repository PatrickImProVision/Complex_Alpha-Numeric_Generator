<?php
include('../Base_CANG_Generators/CANG.php');
$CANG = new CANG;
$CANG->SetLength(8);
$CANG->SetType(5);
$return = $CANG->Generate_ID(249996);//In 30 seconds you should be able to generate the key by this ID
print_r($return);
?>
