<?php
/**
 * Base Test Case Class
 * Provides common testing utilities and assertions
 */

class TestCase {
    protected static $testResults = [];
    protected static $passed = 0;
    protected static $failed = 0;
    
    /**
     * Assert that a condition is true
     */
    protected function assertTrue($condition, $message = '') {
        if ($condition) {
            $this->pass($message ?: 'Assert true');
        } else {
            $this->fail($message ?: 'Expected true, got false');
        }
    }
    
    /**
     * Assert that a condition is false
     */
    protected function assertFalse($condition, $message = '') {
        if (!$condition) {
            $this->pass($message ?: 'Assert false');
        } else {
            $this->fail($message ?: 'Expected false, got true');
        }
    }
    
    /**
     * Assert that two values are equal
     */
    protected function assertEquals($expected, $actual, $message = '') {
        if ($expected == $actual) {
            $this->pass($message ?: "Equals: expected=$expected, actual=$actual");
        } else {
            $this->fail($message ?: "Not equals: expected=$expected, actual=$actual");
        }
    }
    
    /**
     * Assert that two values are strictly equal
     */
    protected function assertStrictEquals($expected, $actual, $message = '') {
        if ($expected === $actual) {
            $this->pass($message ?: "Strict equals: expected=$expected, actual=$actual");
        } else {
            $this->fail($message ?: "Not strict equals: expected=$expected, actual=$actual");
        }
    }
    
    /**
     * Assert that a value is an array
     */
    protected function assertIsArray($value, $message = '') {
        if (is_array($value)) {
            $this->pass($message ?: 'Value is array');
        } else {
            $this->fail($message ?: 'Expected array, got ' . gettype($value));
        }
    }
    
    /**
     * Assert that a value is an instance of a class
     */
    protected function assertInstanceOf($class, $object, $message = '') {
        if ($object instanceof $class) {
            $this->pass($message ?: "Instance of $class");
        } else {
            $this->fail($message ?: "Not instance of $class");
        }
    }
    
    /**
     * Assert that a string contains another string
     */
    protected function assertStringContainsString($needle, $haystack, $message = '') {
        if (strpos($haystack, $needle) !== false) {
            $this->pass($message ?: "String contains '$needle'");
        } else {
            $this->fail($message ?: "String does not contain '$needle'");
        }
    }
    
    /**
     * Assert that a file exists
     */
    protected function assertFileExists($path, $message = '') {
        if (file_exists($path)) {
            $this->pass($message ?: "File exists: $path");
        } else {
            $this->fail($message ?: "File does not exist: $path");
        }
    }
    
    /**
     * Assert that a value is not null
     */
    protected function assertNotNull($value, $message = '') {
        if ($value !== null) {
            $this->pass($message ?: 'Value is not null');
        } else {
            $this->fail($message ?: 'Expected not null, got null');
        }
    }
    
    /**
     * Assert that a value is null
     */
    protected function assertNull($value, $message = '') {
        if ($value === null) {
            $this->pass($message ?: 'Value is null');
        } else {
            $this->fail($message ?: 'Expected null, got ' . print_r($value, true));
        }
    }
    
    /**
     * Assert that count of array matches expected
     */
    protected function assertCount($expected, $array, $message = '') {
        $count = is_countable($array) ? count($array) : 0;
        if ($count == $expected) {
            $this->pass($message ?: "Count equals $expected");
        } else {
            $this->fail($message ?: "Count not equals: expected=$expected, actual=$count");
        }
    }
    
    /**
     * Assert that count is less than or equal to expected
     */
    protected function assertLessThanOrEqual($expected, $value, $message = '') {
        if ($value <= $expected) {
            $this->pass($message ?: "Value <= $expected");
        } else {
            $this->fail($message ?: "Value > $expected");
        }
    }
    
    /**
     * Assert that array has key
     */
    protected function assertArrayHasKey($key, $array, $message = '') {
        if (isset($array[$key]) || array_key_exists($key, $array)) {
            $this->pass($message ?: "Array has key '$key'");
        } else {
            $this->fail($message ?: "Array does not have key '$key'");
        }
    }
    
    /**
     * Mark test as passed
     */
    protected function pass($message = '') {
        self::$passed++;
        echo "  ✓ $message\n";
    }
    
    /**
     * Mark test as failed
     */
    protected function fail($message = '') {
        self::$failed++;
        echo "  ✗ $message\n";
    }
    
    /**
     * Get test statistics
     */
    public static function getStats() {
        return [
            'passed' => self::$passed,
            'failed' => self::$failed,
            'total' => self::$passed + self::$failed
        ];
    }
    
    /**
     * Reset statistics
     */
    public static function resetStats() {
        self::$passed = 0;
        self::$failed = 0;
    }
}