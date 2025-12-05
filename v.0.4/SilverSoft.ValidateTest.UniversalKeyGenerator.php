<?php
Include("./UniversalKeyGenerator.php");
echo "SilverSoft.ValidateTest.UniversalKeyGenerator";
$generator = new UniversalKeyGenerator();
    echo "=== RANDOM GENERATION ===\n";
    echo "API Key: " . $generator->generateApiKey() . "\n";
    echo "Username: " . $generator->generateUsername() . "\n";
    echo "Upload URI: " . $generator->generateUploadUri() . "\n";
    echo "Password: " . $generator->generateUserPassword() . "\n";
    echo "Product Key: " . $generator->generateProductKey() . "\n\n";

    echo "=== SEQUENTIAL GENERATION ===\n";
    $apiKey = $generator->generateApiKey();
    echo "Current API Key: $apiKey\n";
    echo "Next API Key: " . $generator->nextApiKey($apiKey) . "\n";
    echo "Previous API Key: " . $generator->previousApiKey() . "\n\n";

    $productKey = $generator->generateProductKey();
    echo "Current Product Key: $productKey\n";
    echo "Next Product Key: " . $generator->nextProductKey($productKey) . "\n";
    echo "Previous Product Key: " . $generator->previousProductKey() . "\n";
?>