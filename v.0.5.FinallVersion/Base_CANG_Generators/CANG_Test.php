<?php

class CANG_Test
{
    protected CANG $cang;

    public function __construct()
    {
        $this->cang = new CANG(range('A', 'Z'), 8);
    }

    public function run(): void
    {
        echo "=== CANG TEST SUITE ===\n\n";

        $this->testBeginning();
        $this->testEnd();
        $this->testRandom();
        $this->testIdMode();
        $this->testNextPrevious();
        $this->testStringPositionMapping();

        echo "\n=== TESTING COMPLETE ===\n";
    }

    protected function assertEqual($expected, $actual, string $label): void
    {
        $status = ($expected === $actual) ? "PASS" : "FAIL";
        echo sprintf(
            "%-30s : %-5s | Expected: %-12s Got: %-12s\n",
            $label,
            $status,
            $expected,
            $actual
        );
    }

    // ---------------------------------------------------------
    //  INDIVIDUAL TESTS
    // ---------------------------------------------------------

    protected function testBeginning(): void
    {
        echo "--- Beginning Mode ---\n";
        $result = $this->cang->generate(CANG::MODE_BEGINNING);
        $this->assertEqual("AAAAAAAA", $result, "Beginning");
        echo "\n";
    }

    protected function testEnd(): void
    {
        echo "--- End Mode ---\n";
        $result = $this->cang->generate(CANG::MODE_END);
        $this->assertEqual("ZZZZZZZZ", $result, "End");
        echo "\n";
    }

    protected function testRandom(): void
    {
        echo "--- Random Mode ---\n";
        $result = $this->cang->generate(CANG::MODE_RANDOM);
        echo "Random Output: $result\n\n";
    }

    protected function testIdMode(): void
    {
        echo "--- ID Mode ---\n";
        $this->assertEqual("AAAAAAAA", $this->cang->generate(CANG::MODE_ID, "0"), "ID 0");
        $this->assertEqual("AAAAAAAB", $this->cang->generate(CANG::MODE_ID, "1"), "ID 1");
        $this->assertEqual("AAAAAAAC", $this->cang->generate(CANG::MODE_ID, "2"), "ID 2");
        echo "\n";
    }

    protected function testNextPrevious(): void
    {
        echo "--- Next / Previous ---\n";

        $this->assertEqual(
            "AAAAAAAC",
            $this->cang->generate(CANG::MODE_NEXT, "AAAAAAAB"),
            "Next"
        );

        $this->assertEqual(
            "AAAAAAAB",
            $this->cang->generate(CANG::MODE_PREVIOUS, "AAAAAAAC"),
            "Previous"
        );

        echo "\n";
    }

    protected function testStringPositionMapping(): void
    {
        echo "--- String <-> Position ---\n";

        $this->assertEqual(0, $this->cang->stringToPosition("AAAAAAAA"), "Pos AAAAAAAA");
        $this->assertEqual(1, $this->cang->stringToPosition("AAAAAAAB"), "Pos AAAAAAAB");
        $this->assertEqual(2, $this->cang->stringToPosition("AAAAAAAC"), "Pos AAAAAAAC");

        $this->assertEqual("AAAAAAAA", $this->cang->positionToString(0), "Str 0");
        $this->assertEqual("AAAAAAAB", $this->cang->positionToString(1), "Str 1");
        $this->assertEqual("AAAAAAAC", $this->cang->positionToString(2), "Str 2");

        echo "\n";
    }
}

/*
Example: How To Test

require "CANG.php";
require "CANG_Test.php";

$CANGTest = New CANG_Test();
$CANGTest->run();

*/
?>