<?php

class CANG_Benchmark
{
    protected CANG $cang;

    public function __construct()
    {
        $this->cang = new CANG(range('A', 'Z'), 8);
    }

    public function run(int $startId, int $endId, bool $warmup = true): void
    {
        echo "=== CANG BENCHMARK ===\n";
        echo "Range: $startId → $endId\n";
        echo "Total IDs: " . ($endId - $startId + 1) . "\n\n";

        if ($warmup) {
            $this->warmup();
        }

        $this->benchmark($startId, $endId);
    }

    // ---------------------------------------------------------
    //  WARMUP (optional)
    // ---------------------------------------------------------

    protected function warmup(): void
    {
        echo "--- Warmup Pass (stabilizing CPU cache) ---\n";

        $this->cang->generate(CANG::MODE_ID, "0");
        $this->cang->generate(CANG::MODE_ID, "1000");
        $this->cang->generate(CANG::MODE_ID, "500000");

        echo "Warmup complete.\n\n";
    }

    // ---------------------------------------------------------
    //  MAIN BENCHMARK
    // ---------------------------------------------------------

    protected function benchmark(int $startId, int $endId): void
    {
        echo "--- Running Benchmark ---\n";

        $count = $endId - $startId + 1;

        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        for ($i = $startId; $i <= $endId; $i++) {
            $this->cang->generate(CANG::MODE_ID, (string)$i);
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $duration = $endTime - $startTime;
        $memoryUsed = $endMemory - $startMemory;
        $avgPerId = $duration / $count;
        $idsPerSecond = $count / $duration;

        echo "Duration:        " . number_format($duration, 6) . " sec\n";
        echo "Avg per ID:      " . number_format($avgPerId, 9) . " sec\n";
        echo "IDs per second:  " . number_format($idsPerSecond, 2) . "\n";
        echo "Memory used:     " . number_format($memoryUsed / 1024, 2) . " KB\n";

        echo "\n--- Benchmark Complete ---\n";
    }
}

/*
Example: How To Benchmark

require "CANG.php";
require "CANG_Benchmark.php";

$bench = New CANG_Benchmark();

// Example: benchmark 0 → 1,000,000
$bench->run(0, 1000000);
*/
?>