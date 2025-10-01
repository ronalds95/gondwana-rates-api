<?php
use PHPUnit\Framework\TestCase;

class QuickTest extends TestCase
{
    public function testBasicAssertions()
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertEquals(1, 1);
    }
    
    public function testArrayOperations()
    {
        $array = [1, 2, 3];
        $this->assertCount(3, $array);
        $this->assertContains(2, $array);
    }
    
    public function testStringOperations()
    {
        $string = "hello";
        $this->assertEquals(5, strlen($string));
        $this->assertStringContainsString("ell", $string);
    }
}
?>
