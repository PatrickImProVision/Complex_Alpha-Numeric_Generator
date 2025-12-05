<?php
Include("./ComplexKeyGenerator.php");
echo "SilverSoft.ValidateTest.ComplexKeyGenerator";

/*
 * Example usage — ready to run
 */
echo "UniGen examples\n";
echo "---------------\n";
echo "Key (hex, 32): " . UniGen::generateKey(32, 'hex') . PHP_EOL;
echo "Key (base64, 24): " . UniGen::generateKey(24, 'base64') . PHP_EOL;
echo "Custom key (10) from charset 'ABC123': " . UniGen::generateKey(10, 'ABC123') . PHP_EOL;

echo "\nUsername examples\n";
echo "Default username: " . UniGen::generateUsername('', 12) . PHP_EOL;
echo "With prefix 'dev': " . UniGen::generateUsername('dev', 14) . PHP_EOL;

echo "\nURI slug: " . UniGen::generateUriSlug(4, 5) . PHP_EOL;

echo "\nPassword examples\n";
echo "Password (16, mixed): " . UniGen::generatePassword(16, true) . PHP_EOL;
echo "Password (12, only lower+digits): " . UniGen::generatePassword(12, true, ['lower','digits']) . PHP_EOL;

echo "\nAPI Version: " . UniGen::generateApiVer(5, 5) . PHP_EOL;

echo "\nUUID v4: " . UniGen::generateUuidV4() . PHP_EOL;

?>