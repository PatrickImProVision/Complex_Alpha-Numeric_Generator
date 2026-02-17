<?php
declare(strict_types=1);

require_once __DIR__ . '/../CANG_Driver/CANG_Driver.php';

$Driver = new CANG_Driver();

// Microsoft-style setup:
// - Length: 25
// - Language_Id: 4 (Alphabet_Upper_Num)
$Driver->SetLength(25);
$Driver->SetType(4);
$Driver->SetOrder('Order_1');
$Driver->Load();

// Generate by numeric Id.
$Result = $Driver->Id(249996);

// Format code as XXXXX-XXXXX-XXXXX-XXXXX-XXXXX
$Raw_Code = (string) ($Result['Profile']['Code_String'] ?? '');
$Groups = str_split($Raw_Code, 5);
$Formatted_Code = implode('-', $Groups);

$Result['Profile']['Code_String_Formatted'] = $Formatted_Code;

print_r($Result);
