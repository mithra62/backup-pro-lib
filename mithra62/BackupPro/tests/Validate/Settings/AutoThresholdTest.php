<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/tests/SettingsTest.php
 */
namespace mithra62\BackupPro\tests\Validate;

use mithra62\BackupPro\tests\TestFixture;
use mithra62\BackupPro\Validate\Settings\AutoThreshold;

/**
 * Backup Pro - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\BackupPro\Validate\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class AutoThresholdTest extends TestFixture
{
    protected $expected_rules = array(
        'min', 
        'required', 
        'numeric'
    );
    
    public function testObjectInstance()
    {
        $field = new AutoThreshold;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\AbstractField', $field);
    }
    
    public function testFieldNameValue()
    {
        $field = new AutoThreshold;
        $this->assertEquals('auto_threshold', $field->getFieldName());
    }
    
    /**
    public function testCompileRulesReturnInstance()
    {
        $field = new AutoThreshold;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings\AutoThreshold', $field->compileRules());
    }
    
    
    public function testCompiledRules()
    {
        $field = new AutoThreshold;
        $field->compileRules();
        $rules = $field->getRuleNames();
        $this->assertCount(3, $rules['auto_threshold']);
        foreach($this->expected_rules AS $key => $value) {
            $this->assertArrayHasKey($value, $rules['auto_threshold']);
        }
    }
    
    public function testCustomValueEmptyValueReturnErrors()
    {
        $field = new AutoThreshold(array('auto_threshold' => 'custom'));
        $field->compileRules();
        $rules = $field->getRuleNames();
        $this->assertCount(1, $rules['auto_threshold']);
        $this->assertCount(3, $rules['auto_threshold_custom']);
        foreach($this->expected_rules AS $key => $value) {
            $this->assertArrayHasKey($value, $rules['auto_threshold_custom']);
        }
    }
    
    */
    
}
