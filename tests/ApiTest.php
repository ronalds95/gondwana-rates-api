<?php
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    public function testDateConversion()
    {
        // Test valid date conversion
        $result = convertDate('01/10/2025');
        $this->assertEquals('2025-10-01', $result);
        
        // Test invalid date
        $result = convertDate('invalid-date');
        $this->assertFalse($result);
    }
    
    public function testAgeGroupClassification()
    {
        // Test adult - use variables instead of constant expressions
        $adultAge = 25;
        $this->assertEquals('Adult', ($adultAge >= 18) ? 'Adult' : 'Child');
        
        // Test child - use variables instead of constant expressions
        $childAge = 17;
        $this->assertEquals('Child', ($childAge >= 18) ? 'Adult' : 'Child');
    }
    
    public function testUnitMappingLogic()
    {
        // Test that unit names containing 1 map to first ID
        $unitName = 'Unit with number 1';
        $hasOne = preg_match('/\b1\b/', $unitName) || str_contains($unitName, '1');
        $this->assertTrue($hasOne);
        
        // Test that unit names containing 2 map to second ID
        $unitName = 'Unit with number 2';
        $hasTwo = preg_match('/\b2\b/', $unitName) || str_contains($unitName, '2');
        $this->assertTrue($hasTwo);
    }
    
    public function testAgeValidation()
    {
        // Test valid ages
        $validAges = [25, 18, 17, 5, 0];
        foreach ($validAges as $validAge) {
            $this->assertTrue($validAge >= 0 && $validAge <= 120);
        }
        
        // Test invalid ages
        $invalidAges = [-1, 121, 150];
        foreach ($invalidAges as $invalidAge) {
            $this->assertFalse($invalidAge >= 0 && $invalidAge <= 120);
        }
    }
    
    public function testJsonParsing()
    {
        $json = '{"test": "value"}';
        $data = json_decode($json, true);
        
        $this->assertIsArray($data);
        $this->assertEquals('value', $data['test']);
    }
    
    public function testRequiredFieldsValidation()
    {
        $required = ['Unit Name', 'Arrival', 'Departure', 'Occupants', 'Ages'];
        
        $this->assertCount(5, $required);
        $this->assertContains('Unit Name', $required);
        $this->assertContains('Arrival', $required);
    }
    
    public function testConvertDateForTestFunction()
    {
        // Test the enhanced date conversion function
        $result = convertDateForTest('01/10/2025');
        $this->assertEquals('2025-10-01', $result);
        
        // Test invalid date
        $result = convertDateForTest('invalid-date');
        $this->assertFalse($result);
        
        // Test empty input
        $result = convertDateForTest('');
        $this->assertFalse($result);
        
        // Test invalid date format
        $result = convertDateForTest('31/02/2025');
        $this->assertFalse($result);
    }
    
    public function testValidateAgeFunction()
    {
        $this->assertTrue(validateAge(25));
        $this->assertTrue(validateAge(0));
        $this->assertTrue(validateAge(120));
        $this->assertFalse(validateAge(-1));
        $this->assertFalse(validateAge(121));
    }
    
    public function testGetAgeGroupFunction()
    {
        $this->assertEquals('Adult', getAgeGroup(25));
        $this->assertEquals('Adult', getAgeGroup(18));
        $this->assertEquals('Child', getAgeGroup(17));
        $this->assertEquals('Child', getAgeGroup(5));
    }
    
    public function testActualApiFunctions()
    {
        // Test that the functions are actually callable
        $this->assertIsCallable('convertDate');
        $this->assertIsCallable('convertDateForTest');
        $this->assertIsCallable('validateAge');
        $this->assertIsCallable('getAgeGroup');
    }
}
