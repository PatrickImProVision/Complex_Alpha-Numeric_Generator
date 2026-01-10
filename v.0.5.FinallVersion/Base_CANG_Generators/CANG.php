<?php

class CANG
{
    // ---------------------------------------------------------
    //  CONFIGURATION
    // ---------------------------------------------------------

    protected array $language = [];
    protected int $length = 8;

    public const MODE_BEGINNING = 'Beginning';
    public const MODE_CURRENT   = 'Current';
    public const MODE_NEXT      = 'Next';
    public const MODE_PREVIOUS  = 'Previous';
    public const MODE_END       = 'End';
    public const MODE_RANDOM    = 'Random';
    public const MODE_ID        = 'Id';

    // ---------------------------------------------------------
    //  CONSTRUCTOR
    // ---------------------------------------------------------

    public function __construct(array $languageDefinition, int $length = 8)
    {
        $this->setLanguage($languageDefinition);
        $this->setLength($length);
    }

    // ---------------------------------------------------------
    //  LANGUAGE + LENGTH
    // ---------------------------------------------------------

    public function setLanguage(array $languageDefinition): void
    {
        if (empty($languageDefinition)) {
            throw new InvalidArgumentException("Language definition cannot be empty.");
        }

        $this->language = array_values($languageDefinition);
    }

    public function setLength(int $length): void
    {
        if ($length < 8) {
            throw new InvalidArgumentException("Minimum length is 8.");
        }
        $this->length = $length;
    }

    // ---------------------------------------------------------
    //  CORE: POSITION → STRING
    // ---------------------------------------------------------

    public function positionToString(int $position): string
    {
        if ($position < 0) {
            throw new InvalidArgumentException("Position cannot be negative.");
        }

        $base = count($this->language);
        $result = '';

        while ($position >= 0) {
            $result = $this->language[$position % $base] . $result;
            $position = intdiv($position, $base) - 1;
        }

        return str_pad($result, $this->length, $this->language[0], STR_PAD_LEFT);
    }

    // ---------------------------------------------------------
    //  CORE: STRING → POSITION
    // ---------------------------------------------------------

    public function stringToPosition(string $string): int
    {
        if (strlen($string) !== $this->length) {
            throw new InvalidArgumentException("Input string must be exactly {$this->length} characters.");
        }

        $base = count($this->language);
        $chars = str_split($string);
        $position = 0;

        foreach ($chars as $char) {
            $index = array_search($char, $this->language, true);
            if ($index === false) {
                throw new InvalidArgumentException("Invalid character '$char' in input.");
            }
            $position = $position * $base + ($index + 1);
        }

        return $position - 1;
    }

    // ---------------------------------------------------------
    //  GENERATION MODES
    // ---------------------------------------------------------

    public function generate(string $mode, ?string $current = null): string
    {
        switch ($mode) {

            case self::MODE_BEGINNING:
                return str_repeat($this->language[0], $this->length);

            case self::MODE_END:
                return str_repeat(end($this->language), $this->length);

            case self::MODE_RANDOM:
                return $this->randomString();

            case self::MODE_CURRENT:
                if ($current === null) {
                    throw new InvalidArgumentException("Current mode requires input string.");
                }
                return $current;

            case self::MODE_NEXT:
                if ($current === null) {
                    throw new InvalidArgumentException("Next mode requires input string.");
                }
                return $this->positionToString($this->stringToPosition($current) + 1);

            case self::MODE_PREVIOUS:
                if ($current === null) {
                    throw new InvalidArgumentException("Previous mode requires input string.");
                }
                return $this->positionToString(
                    max(0, $this->stringToPosition($current) - 1)
                );

            case self::MODE_ID:
                if ($current === null || !ctype_digit((string)$current)) {
                    throw new InvalidArgumentException("Id mode requires a numeric input.");
                }
                return $this->positionToString((int)$current);

            default:
                throw new InvalidArgumentException("Unknown mode '$mode'.");
        }
    }

    // ---------------------------------------------------------
    //  RANDOM GENERATOR
    // ---------------------------------------------------------

    protected function randomString(): string
    {
        $result = '';
        $max = count($this->language) - 1;

        for ($i = 0; $i < $this->length; $i++) {
            $result .= $this->language[random_int(0, $max)];
        }

        return $result;
    }
}
/*
Example: How To Use

$language = range('A', 'Z');
$CANG = New CANG($language, 8);

echo $CANG->generate(CANG::MODE_BEGINNING); // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "0");   // AAAAAAAA
echo $CANG->generate(CANG::MODE_ID, "1");   // AAAAAAAB
echo $CANG->generate(CANG::MODE_ID, "2");   // AAAAAAAC

echo $CANG->generate(CANG::MODE_NEXT, "AAAAAAAB"); // AAAAAAAC
echo $CANG->stringToPosition("AAAAAAAC");          // 2
*/
?>