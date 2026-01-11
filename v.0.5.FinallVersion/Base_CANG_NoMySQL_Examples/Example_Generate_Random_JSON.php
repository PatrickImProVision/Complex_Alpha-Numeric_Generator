<?php
include('../Base_CANG_Generators/CANG.php');
$CANG = new CANG;
$CANG->SetLength(8);
$CANG->SetType(5);
$return = $CANG->Generate_String();
header("Content-type:application/json");
echo json_encode($return,true);
?>
