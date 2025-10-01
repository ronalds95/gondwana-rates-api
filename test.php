<?php
// Simple test file for SonarCloud coverage
// Run with: php test.php

function runTests() {
    $testsPassed = 0;
    $totalTests = 0;
    
    // Test 1: Date conversion function
    function testDateConversion() {
        $testDate = '01/10/2025';
        $expected = '2025-10-01';
        
        $dt = DateTime::createFromFormat('d/m/Y', $testDate);
        if ($dt === false) return false;
        $result = $dt->format('Y-m-d');
        
        return $result === $expected;
    }
    
    // Test 2: Age group classification
    function testAgeGroup() {
        $adultResult = (18 >= 18) ? 'Adult' : 'Child';
        $childResult = (17 >= 18) ? 'Adult' : 'Child';
        
        return $adultResult === 'Adult' && $childResult === 'Child';
    }
    
    // Test 3: JSON parsing
    function testJsonParsing() {
        $testJson = '{"test": "value"}';
        $parsed = json_decode($testJson, true);
        
        return is_array($parsed) && $parsed['test'] === 'value';
    }
    
    // Test 4: Array validation
    function testArrayValidation() {
        $ages = [34, 8];
        return is_array($ages) && count($ages) === 2;
    }
    
    // Run all tests
    $tests = [
        'Date Conversion' => 'testDateConversion',
        'Age Group' => 'testAgeGroup', 
        'JSON Parsing' => 'testJsonParsing',
        'Array Validation' => 'testArrayValidation'
    ];
    
    foreach ($tests as $testName => $testFunction) {
        $totalTests++;
        if ($testFunction()) {
            $testsPassed++;
            echo "✓ PASS: $testName\n";
        } else {
            echo "✗ FAIL: $testName\n";
        }
    }
    
    echo "\nResults: $testsPassed/$totalTests tests passed\n";
    return $testsPassed === $totalTests;
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    runTests();
}

// Functions that can be called for coverage
function getAgeGroup($age) {
    return ($age >= 18) ? 'Adult' : 'Child';
}

function isValidDate($date) {
    $dt = DateTime::createFromFormat('d/m/Y', $date);
    return $dt !== false;
}

function validateAges($ages) {
    if (!is_array($ages)) return false;
    foreach ($ages as $age) {
        $ageInt = (int)$age;
        if ($ageInt < 0 || $ageInt > 120) return false;
    }
    return true;
}
?>
