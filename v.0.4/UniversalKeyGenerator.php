<?php
// filepath: c:\Users\ImPro\LocalHostXampp\htdocs\SilverSoft.Projects\UniversalKeyGenerator.php

class UniversalKeyGenerator {
    private $currentState = [];

    /**
     * Generate a random API Key (8 uppercase letters)
     */
    public function generateApiKey() {
        return $this->generateRandomString(8, 'UPPERCASE');
    }

    /**
     * Generate a random Username (8-12 characters, mixed case + numbers)
     */
    public function generateUsername() {
        return $this->generateRandomString(mt_rand(8, 12), 'MIXED');
    }

    /**
     * Generate a random Upload URI (format: /upload/XXXXXXXX)
     */
    public function generateUploadUri() {
        $randomPart = $this->generateRandomString(8, 'UPPERCASE');
        return "/upload/{$randomPart}";
    }

    /**
     * Generate a random User Password (max 16 characters, any letters)
     */
    public function generateUserPassword() {
        $length = mt_rand(8, 16);
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return $this->generateCustomString($length, $charset);
    }

    /**
     * Generate a Product Key (format: XXXXX-XXXXX-XXXXX-XXXXX-XXXXX)
     */
    public function generateProductKey() {
        $segments = [];
        for ($i = 0; $i < 5; $i++) {
            $segments[] = $this->generateRandomString(5, 'UPPERCASE');
        }
        return implode('-', $segments);
    }

    /**
     * Get next API Key based on current
     */
    public function nextApiKey($current = null) {
        if ($current === null && isset($this->currentState['apiKey'])) {
            $current = $this->currentState['apiKey'];
        }
        $next = $this->incrementString($current ?? $this->generateApiKey(), 'UPPERCASE');
        $this->currentState['apiKey'] = $next;
        return $next;
    }

    /**
     * Get previous API Key based on current
     */
    public function previousApiKey($current = null) {
        if ($current === null && isset($this->currentState['apiKey'])) {
            $current = $this->currentState['apiKey'];
        }
        $prev = $this->decrementString($current ?? $this->generateApiKey(), 'UPPERCASE');
        $this->currentState['apiKey'] = $prev;
        return $prev;
    }

    /**
     * Get next Product Key
     */
    public function nextProductKey($current = null) {
        if ($current === null && isset($this->currentState['productKey'])) {
            $current = $this->currentState['productKey'];
        }
        $next = $this->incrementProductKey($current ?? $this->generateProductKey());
        $this->currentState['productKey'] = $next;
        return $next;
    }

    /**
     * Get previous Product Key
     */
    public function previousProductKey($current = null) {
        if ($current === null && isset($this->currentState['productKey'])) {
            $current = $this->currentState['productKey'];
        }
        $prev = $this->decrementProductKey($current ?? $this->generateProductKey());
        $this->currentState['productKey'] = $prev;
        return $prev;
    }

    /**
     * Helper: Generate random string of specified type
     */
    private function generateRandomString($length, $type = 'UPPERCASE') {
        $charsets = [
            'UPPERCASE' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'LOWERCASE' => 'abcdefghijklmnopqrstuvwxyz',
            'MIXED' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            'NUMBERS' => '0123456789'
        ];

        $charset = $charsets[$type] ?? $charsets['MIXED'];
        $result = '';
        $maxIndex = strlen($charset) - 1;

        for ($i = 0; $i < $length; $i++) {
            $result .= $charset[mt_rand(0, $maxIndex)];
        }

        return $result;
    }

    /**
     * Helper: Generate custom string with custom charset
     */
    private function generateCustomString($length, $charset) {
        $result = '';
        $maxIndex = strlen($charset) - 1;

        for ($i = 0; $i < $length; $i++) {
            $result .= $charset[mt_rand(0, $maxIndex)];
        }

        return $result;
    }

    /**
     * Helper: Increment string (A->B, Z->AA)
     */
    private function incrementString($str, $type = 'UPPERCASE') {
        $charset = ($type === 'UPPERCASE') ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : 'abcdefghijklmnopqrstuvwxyz';
        $result = str_split($str);
        $carry = 1;

        for ($i = count($result) - 1; $i >= 0 && $carry; $i--) {
            $pos = strpos($charset, $result[$i]);
            if ($pos !== false) {
                $pos += $carry;
                if ($pos >= strlen($charset)) {
                    $result[$i] = $charset[0];
                    $carry = 1;
                } else {
                    $result[$i] = $charset[$pos];
                    $carry = 0;
                }
            }
        }

        if ($carry) {
            array_unshift($result, $charset[0]);
        }

        return implode('', $result);
    }

    /**
     * Helper: Decrement string (B->A, AA->Z)
     */
    private function decrementString($str, $type = 'UPPERCASE') {
        $charset = ($type === 'UPPERCASE') ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : 'abcdefghijklmnopqrstuvwxyz';
        $result = str_split($str);
        $borrow = 1;

        for ($i = count($result) - 1; $i >= 0 && $borrow; $i--) {
            $pos = strpos($charset, $result[$i]);
            if ($pos !== false) {
                $pos -= $borrow;
                if ($pos < 0) {
                    $result[$i] = $charset[strlen($charset) - 1];
                    $borrow = 1;
                } else {
                    $result[$i] = $charset[$pos];
                    $borrow = 0;
                }
            }
        }

        if (count($result) > 1 && $result[0] === $charset[strlen($charset) - 1]) {
            array_shift($result);
        }

        return implode('', $result);
    }

    /**
     * Helper: Increment Product Key
     */
    private function incrementProductKey($key) {
        $segments = explode('-', $key);
        $carry = 1;

        for ($i = count($segments) - 1; $i >= 0 && $carry; $i--) {
            $segments[$i] = $this->incrementString($segments[$i], 'UPPERCASE');
            if ($segments[$i] !== 'A' || $segments[$i] === 'AAAAAA') {
                $carry = 0;
            }
        }

        return implode('-', $segments);
    }

    /**
     * Helper: Decrement Product Key
     */
    private function decrementProductKey($key) {
        $segments = explode('-', $key);
        $borrow = 1;

        for ($i = count($segments) - 1; $i >= 0 && $borrow; $i--) {
            $prev = $segments[$i];
            $segments[$i] = $this->decrementString($segments[$i], 'UPPERCASE');
            if ($segments[$i] !== $prev) {
                $borrow = 0;
            }
        }

        return implode('-', $segments);
    }

    /**
     * Reset current state
     */
    public function resetState() {
        $this->currentState = [];
    }
}

// Example Usage:
if (php_sapi_name() === 'cli') {
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
}
?>