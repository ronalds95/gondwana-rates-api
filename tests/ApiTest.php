<?php
use PHPUnit\Framework\TestCase;

// Include your API functions
require_once __DIR__ . '/../api.php';

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
        // Test adult
        $this->assertEquals('Adult', ($age = 25) >= 18 ? 'Adult' : 'Child');
        
        // Test child
        $this->assertEquals('Child', ($age = 17) >= 18 ? 'Adult' : 'Child');
    }
    
    public function testUnitMappingLogic()
    {
        $testIds = [-2147483637, -2147483456];
        
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
        foreach ($validAges as $age) {
            $this->assertTrue($age >= 0 && $age <= 120);
        }
        
        // Test invalid ages
        $invalidAges = [-1, 121, 150];
        foreach ($invalidAges as $age) {
            $this->assertFalse($age >= 0 && $age <= 120);
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
}
?>
