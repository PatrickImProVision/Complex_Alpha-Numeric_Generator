<?php
include('../Base_CANG_Generators/MS_G.php');
$MS_G = new MS_G;
$return = $MS_G->byRAND();//In 30 seconds you should be able to generate the key by this ID
header("Content-type:application/json");
echo json_encode($return,true)
?>
