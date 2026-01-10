<?php
declare(strict_types=1);

class UniqueKeyGenerator
{
    private const TYPE_ALPHA_UPPERLOWER = 1;
    private const TYPE_ALPHA_UPPER_NUM = 2;
    private const TYPE_ALPHA_LOWER_NUM = 3;
    private const TYPE_ALPHA_NUM = 4;
    private const TYPE_URLSAFE = 5;

    private array $chars;
    private array $indexMap;
    private int $length;
    private int $type;
    private int $maxType = 5;

    public function __construct(int $length = 1, int $type = self::TYPE_ALPHA_UPPERLOWER)
    {
        $this->length = max(1, $length);
        $this->type = min(max(1, $type), $this->maxType);
        $this->chars = $this->buildCharset($this->type);
        $this->indexMap = array_flip($this->chars);
    }

    private function buildCharset(int $type): array
    {
        return match ($type) {
            self::TYPE_ALPHA_UPPER_NUM => array_merge(range('A', 'Z'), range('0', '9')),
            self::TYPE_ALPHA_LOWER_NUM => array_merge(range('a', 'z'), range('0', '9')),
            self::TYPE_ALPHA_NUM => array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9')),
            self::TYPE_URLSAFE => array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'), ['-', '_']),
            default => array_merge(range('A', 'Z'), range('a', 'z')),
        };
    }

    /**
     * Generate a random (or deterministic all-first) code.
     */
    public function generateRandom(bool $secure = true, bool $useFirstChar = false): string
    {
        $out = '';
        $max = count($this->chars) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            if ($useFirstChar) {
                $out .= $this->chars[0];
                continue;
            }
            $idx = $secure ? random_int(0, $max) : mt_rand(0, $max);
            $out .= $this->chars[$idx];
        }
        return $out;
    }

    /**
     * Generate next code in sequence. Returns associative payload similar to original.
     * Throws InvalidArgumentException on invalid input.
     */
    public function next(string $current): array
    {
        $time = time();
        if ($current === '') {
            throw new InvalidArgumentException('Current code must be non-empty.');
        }

        // validate characters
        $charsCount = count($this->chars);
        $currentArr = preg_split('//u', $current, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($currentArr as $ch) {
            if (!isset($this->indexMap[$ch])) {
                throw new InvalidArgumentException('Current code contains invalid character: ' . $ch);
            }
        }

        // convert to indices
        $indices = array_map(fn($c) => $this->indexMap[$c], $currentArr);

        // increment with carry
        $pos = count($indices) - 1;
        $carry = 1;
        while ($pos >= 0 && $carry) {
            $indices[$pos] += $carry;
            if ($indices[$pos] >= $charsCount) {
                $indices[$pos] = 0;
                $carry = 1;
                $pos--;
            } else {
                $carry = 0;
            }
        }

        if ($carry === 1) {
            // overflow: increase length by 1 (consistent with original behaviour)
            array_unshift($indices, 0);
        }

        // build code from indices
        $newChars = array_map(fn($i) => $this->chars[$i], $indices);
        $codeBase = implode('', $newChars);

        // compute simple base-n count (may overflow native ints for huge lengths)
        $countNumber = $this->baseNToInteger($indices, $charsCount);

        // prepare payload (single place)
        return [
            'code_base' => $codeBase,
            'code_base_md5' => md5($codeBase),
            'code_base_sha1' => sha1($codeBase),
            'code_base64_encode' => base64_encode($codeBase),
            'code_time' => $time,
            'code_count' => $countNumber,
            'code_range' => $this->safePowString($charsCount, $this->length),
            'code_message' => (strlen($codeBase) > $this->length) ? 'is_upper_or_full' : '',
            'code_name' => $this->charsetName(),
            'code_type' => $this->type,
            'code_max_type' => $this->maxType,
            'code_length' => $this->length,
        ];
    }

    private function charsetName(): string
    {
        return match ($this->type) {
            self::TYPE_ALPHA_UPPER_NUM => '[A-Z,0-9]',
            self::TYPE_ALPHA_LOWER_NUM => '[a-z,0-9]',
            self::TYPE_ALPHA_NUM => '[A-Z,a-z,0-9]',
            self::TYPE_URLSAFE => '[A-Z,a-z,0-9,-_]',
            default => '[A-Z,a-z]',
        };
    }

    /**
     * Convert base-n digits to integer (may return string if large).
     */
    private function baseNToInteger(array $digits, int $base)
    {
        // prefer BCMath for exact big integers when available
        if (function_exists('bcadd')) {
            $acc = '0';
            foreach ($digits as $d) {
                $acc = bcmul($acc, (string)$base);
                $acc = bcadd($acc, (string)$d);
            }
            return $acc;
        }

        // fallback to native integer (may overflow)
        $acc = 0;
        foreach ($digits as $d) {
            $acc = $acc * $base + $d;
        }
        return $acc;
    }

    private function safePowString(int $base, int $exp): string
    {
        if (function_exists('bcpow')) {
            return bcpow((string)$base, (string)$exp);
        }
        // fallback (may be float and imprecise for large exp)
        return (string) pow($base, $exp);
    }
}

// Example usage:
$g = new UniqueKeyGenerator(8, 5);
$random = $g->generateRandom();
$nextInfo = $g->next('aZ5sTp53x');
var_dump($random, $nextInfo);

?>
