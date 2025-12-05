<?php
declare(strict_types=1);

class UniversalGenerator
{
    // Generate random uppercase string (A-Z)
    public static function randomUpper(int $length = 8): string
    {
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= chr(random_int(65, 90)); // A-Z
        }
        return $out;
    }

    // Generate deterministic uppercase string from integer index (base-26, padded)
    public static function fromIndexUpper(int $index, int $length = 8): string
    {
        if ($index < 0) {
            throw new InvalidArgumentException("Index must be >= 0");
        }
        $chars = [];
        $v = $index;
        while ($v > 0) {
            $rem = $v % 26;
            $chars[] = chr(65 + $rem);
            $v = intdiv($v, 26);
        }
        while (count($chars) < $length) {
            $chars[] = 'A';
        }
        $chars = array_reverse($chars);
        $s = implode('', array_slice($chars, -1 * $length));
        return $s;
    }

    // ApiKey: random or deterministic by index
    public static function apiKey(?int $index = null, int $length = 8): string
    {
        return $index === null ? self::randomUpper($length) : self::fromIndexUpper($index, $length);
    }

    // UserName: same pattern as ApiKey (capitals)
    public static function userName(?int $index = null, int $length = 8): string
    {
        return self::apiKey($index, $length);
    }

    // UploadUri: returns a path-friendly token (caps) optionally prefixed
    public static function uploadUri(?int $index = null, int $length = 8, string $prefix = '/upload/'): string
    {
        $token = $index === null ? self::randomUpper($length) : self::fromIndexUpper($index, $length);
        return rtrim($prefix, '/') . '/' . $token;
    }

    // Random password: letters (upper+lower) and digits, max length 16
    public static function password(int $length = 16, bool $includeDigits = true): string
    {
        if ($length < 1) throw new InvalidArgumentException("Length must be >= 1");
        if ($length > 16) $length = 16;
        $chars = array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            $includeDigits ? range('0','9') : []
        );
        $out = '';
        $max = count($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[random_int(0, $max)];
        }
        return $out;
    }

    // ProductKey: deterministic from index or random; format groups e.g. 5 groups of 5 capitals
    public static function productKey(?int $index = null, int $groups = 5, int $groupLen = 5): string
    {
        $total = $groups * $groupLen;
        if ($index === null) {
            // random version
            $s = self::randomUpper($total);
        } else {
            // deterministic: expand index across total length
            $s = self::fromIndexUpper($index, $total);
        }
        $parts = [];
        for ($i = 0; $i < $groups; $i++) {
            $parts[] = substr($s, $i * $groupLen, $groupLen);
        }
        return implode('-', $parts);
    }

    // Next index
    public static function nextIndex(int $index): int
    {
        if ($index < 0) throw new InvalidArgumentException("Index must be >= 0");
        return $index + 1;
    }

    // Previous index (returns null when no previous)
    public static function previousIndex(int $index): ?int
    {
        if ($index <= 0) return null;
        return $index - 1;
    }
}

?>