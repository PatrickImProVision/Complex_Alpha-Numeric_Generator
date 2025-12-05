<?php
Include("./UniversalGenerator.php");
echo "SilverSoft.ValidateTest.UniversalGenerator";

// Example CLI/demo usage (remove or comment when including in production)
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['argv'][0])) {
    echo "Random ApiKey: " . UniversalGenerator::apiKey(null, 8) . PHP_EOL;
    echo "Deterministic ApiKey (index=123): " . UniversalGenerator::apiKey(123, 8) . PHP_EOL;
    echo "UserName (index=123): " . UniversalGenerator::userName(123, 8) . PHP_EOL;
    echo "UploadUri (index=123): " . UniversalGenerator::uploadUri(123, 8, '/upload') . PHP_EOL;
    echo "Password (max 16): " . UniversalGenerator::password(16) . PHP_EOL;
    echo "ProductKey (index=123): " . UniversalGenerator::productKey(123) . PHP_EOL;
    echo "Next of 123: " . UniversalGenerator::nextIndex(123) . PHP_EOL;
    echo "Prev of 123: " . var_export(UniversalGenerator::previousIndex(123), true) . PHP_EOL;
} else {
    echo "Random ApiKey: " . UniversalGenerator::apiKey(null, 8) . PHP_EOL;
    echo "Deterministic ApiKey (index=123): " . UniversalGenerator::apiKey(123, 8) . PHP_EOL;
    echo "UserName (index=123): " . UniversalGenerator::userName(123, 8) . PHP_EOL;
    echo "UploadUri (index=123): " . UniversalGenerator::uploadUri(123, 8, '/upload') . PHP_EOL;
    echo "Password (max 16): " . UniversalGenerator::password(16) . PHP_EOL;
    echo "ProductKey (index=123): " . UniversalGenerator::productKey(123) . PHP_EOL;
    echo "Next of 123: " . UniversalGenerator::nextIndex(123) . PHP_EOL;
    echo "Prev of 123: " . var_export(UniversalGenerator::previousIndex(123), true) . PHP_EOL;
}

?>