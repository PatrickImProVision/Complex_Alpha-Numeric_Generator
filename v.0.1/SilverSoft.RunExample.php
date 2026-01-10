<?php
declare(strict_types=1);

require_once __DIR__ . '/alphanumeric-generator.php';

$currentNumber = 'aZ5sTp53x';

// Ensure generator function is available
if (!function_exists('anderson_makiyama_get_next_alphanumeric')) {
    $msg = 'Required function anderson_makiyama_get_next_alphanumeric() is missing. Check alphanumeric-generator.php';
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, $msg . PHP_EOL);
    } else {
        echo '<p style="color:red">' . htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE) . '</p>';
    }
    exit(1);
}

// Get next number (safe call)
$nextNumber = anderson_makiyama_get_next_alphanumeric($currentNumber);

// Prepare output mode
$isWeb = PHP_SAPI !== 'cli';
$lineSep = $isWeb ? '<br>' : PHP_EOL;

// Print header (small HTML wrapper for browser)
if ($isWeb) {
    echo '<!doctype html><meta charset="utf-8"><title>Alphanumeric generator</title>';
    echo '<h2>Single step</h2>';
    echo '<div>Current = ' . htmlspecialchars($currentNumber, ENT_QUOTES | ENT_SUBSTITUTE) . '</div>';
    echo '<div>Next    = ' . htmlspecialchars($nextNumber, ENT_QUOTES | ENT_SUBSTITUTE) . '</div>';
    echo '<hr>';
} else {
    echo "Current = {$currentNumber}{$lineSep}";
    echo "Next    = {$nextNumber}{$lineSep}";
    echo str_repeat('-', 40) . $lineSep;
}

// Generate many sequential codes (buffered for performance)
$count = 5000;
$maxAllowed = 100000; // safety cap
if ($count > $maxAllowed) {
    $count = $maxAllowed;
}

$code = $currentNumber;
$lines = [];
$start = microtime(true);
for ($i = 0; $i < $count; $i++) {
    // If generator can throw, consider wrapping this call in try/catch
    $code = anderson_makiyama_get_next_alphanumeric($code);
    $lines[] = $isWeb ? htmlspecialchars($code, ENT_QUOTES | ENT_SUBSTITUTE) : $code;
}
$elapsed = microtime(true) - $start;

// Output buffered results
if ($isWeb) {
    echo '<h3>A lot of sequential alphanumeric strings</h3><div style="font-family:monospace;white-space:pre-line">';
    echo implode('<br>', $lines);
    echo '</div>';
    echo '<hr><small>Generated ' . count($lines) . ' items in ' . number_format($elapsed, 4) . 's</small>';
} else {
    echo "Generated " . count($lines) . " items in " . number_format($elapsed, 4) . "s{$lineSep}";
    echo implode($lineSep, $lines) . $lineSep;
}
