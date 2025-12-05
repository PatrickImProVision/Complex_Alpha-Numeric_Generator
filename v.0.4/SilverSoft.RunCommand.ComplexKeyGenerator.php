<?php
declare(strict_types=1);

/**
 * UniGen.php
 * Universal and Complex Generator For Keys, UserNames, Uri, Passwords, Api Verze XXXXX-XXXXX-... 
 *
 * Ready-to-run single-file implementation with examples.
 *
 * Save as UniGen.php and run from CLI: php UniGen.php
 * Or place in your webroot and open in a browser.
 */

if (!headers_sent()) {
    // default to plain text for browser output when opened directly
    @header('Content-Type: text/plain; charset=utf-8');
}

class UniGen
{
    // Common character sets
    protected static array $sets = [
        'lower'   => 'abcdefghijklmnopqrstuvwxyz',
        'upper'   => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'digits'  => '0123456789',
        'special' => '!@#$%&*()-_=+[]{}<>?'
    ];

    // Secure random character from string
    protected static function randomChar(string $chars): string
    {
        $max = mb_strlen($chars, '8bit') - 1;
        return $chars[random_int(0, $max)];
    }

    // Build random string from provided charset
    protected static function randomString(int $length, string $charset): string
    {
        if ($length <= 0) {
            return '';
        }
        $out = '';
        $clen = strlen($charset);
        if ($clen === 0) {
            throw new InvalidArgumentException('Charset must not be empty');
        }
        for ($i = 0; $i < $length; $i++) {
            $out .= $charset[random_int(0, $clen - 1)];
        }
        return $out;
    }

    // Generate generic key (hex, base64 or custom char set)
    public static function generateKey(int $length = 32, string $type = 'hex'): string
    {
        if ($length <= 0) return '';

        if ($type === 'hex') {
            // return hex string (length is number of chars)
            $bytes = random_bytes((int)ceil($length / 2));
            return substr(bin2hex($bytes), 0, $length);
        }
        if ($type === 'base64') {
            $bytes = random_bytes($length);
            return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
        }
        // custom: use provided type as charset
        return self::randomString($length, $type);
    }

    // Generate username: optional prefix, random suffix; length is total length if provided
    public static function generateUsername(string $prefix = '', int $length = 10, string $sep = '_'): string
    {
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', $prefix);
        $available = $length - strlen($prefix);
        if ($available <= 0) {
            return substr($prefix, 0, $length);
        }
        // small adjective + noun fallback arrays
        $adjectives = ['quick','clever','silent','bright','brave','calm','wild','mighty'];
        $nouns = ['fox','owl','hawk','pixel','node','byte','raven','spark'];
        $part = $adjectives[random_int(0, count($adjectives)-1)]
              . $nouns[random_int(0, count($nouns)-1)];
        // append random digits to fulfill length
        $digitsNeeded = max(0, $available - strlen($part));
        $suffix = substr($part . self::randomString($digitsNeeded, self::$sets['digits']), 0, $available);
        return $prefix === '' ? $suffix : ($prefix . $sep . $suffix);
    }

    // Generate URI-safe slug: groups count and each group's length
    public static function generateUriSlug(int $groups = 3, int $groupLength = 6, string $sep = '-'): string
    {
        if ($groups <= 0 || $groupLength <= 0) return '';
        $chars = strtolower(self::$sets['lower'] . self::$sets['digits']);
        $parts = [];
        for ($g = 0; $g < $groups; $g++) {
            $parts[] = self::randomString($groupLength, $chars);
        }
        return implode($sep, $parts);
    }

    // Generate password with options: ensureMixed ensures at least one char from each requested set
    public static function generatePassword(int $length = 16, bool $ensureMixed = true, array $includeSets = ['upper','lower','digits','special']): string
    {
        if ($length <= 0) return '';

        // build charset
        $charset = '';
        $validSets = [];
        foreach ($includeSets as $s) {
            if (isset(self::$sets[$s])) {
                $charset .= self::$sets[$s];
                $validSets[] = $s;
            }
        }
        if ($charset === '') {
            $charset = self::$sets['lower'] . self::$sets['digits'];
            $validSets = ['lower', 'digits'];
        }

        if (!$ensureMixed || count($validSets) === 1) {
            return self::randomString($length, $charset);
        }

        // Ensure at least one char from each selected set
        $passwordChars = [];
        foreach ($validSets as $s) {
            $passwordChars[] = self::randomChar(self::$sets[$s]);
        }

        // Fill remaining
        $remaining = $length - count($passwordChars);
        for ($i = 0; $i < $remaining; $i++) {
            $passwordChars[] = self::randomChar($charset);
        }

        // Shuffle securely
        $shuffled = [];
        $len = count($passwordChars);
        while ($len) {
            $idx = random_int(0, $len - 1);
            $shuffled[] = $passwordChars[$idx];
            array_splice($passwordChars, $idx, 1);
            $len--;
        }
        return implode('', $shuffled);
    }

    // Generate API version like XXXXX-XXXXX-XXXXX-XXXXX-XXXXX (uppercase letters and digits)
    public static function generateApiVer(int $groups = 5, int $groupLength = 5, string $sep = '-'): string
    {
        if ($groups <= 0 || $groupLength <= 0) return '';
        $chars = self::$sets['upper'] . self::$sets['digits'];
        $parts = [];
        for ($g = 0; $g < $groups; $g++) {
            $parts[] = strtoupper(self::randomString($groupLength, $chars));
        }
        return implode($sep, $parts);
    }

    // Convenience: generate RFC4122 v4 UUID
    public static function generateUuidV4(): string
    {
        $data = random_bytes(16);
        // set version to 0100
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // set bits 6-7 to 10
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

?>