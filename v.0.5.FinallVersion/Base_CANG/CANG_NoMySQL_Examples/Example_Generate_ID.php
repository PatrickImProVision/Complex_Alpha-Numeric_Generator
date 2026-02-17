<?php
declare(strict_types=1);

require_once __DIR__ . '/../CANG_Driver/CANG_Driver.php';

$Driver = new CANG_Driver();

// 1) Set default length for generation.
$Driver->SetLength(8);

// 2) Select profile id (Language_Id).
$Driver->SetType(5);

// 3) Optional: select order key when multiple profile permutations are available.
$Driver->SetOrder('Order_1');

// 4) Load selected profile into core runtime.
$Driver->Load();

// 5) Generate code by explicit numeric Id.
$Return = $Driver->Id(249996);

print_r($Return);
