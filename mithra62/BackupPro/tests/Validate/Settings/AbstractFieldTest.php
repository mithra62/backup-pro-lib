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
use mithra62\BackupPro\Validate\AbstractField;

class _abstractFieldTestMock extends AbstractField
{
    public function compileRules() {}
}

/**
 * Backup Pro - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\BackupPro\Validate\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class AbstractFieldTest extends TestFixture
{
    public function testContextPropertyDefault()
    {
        $field = new _abstractFieldTestMock;
        $this->assertObjectHasAttribute('context', $field);
        $this->assertNull($field->getContext());        
    }
    
    public function testFieldNamePropertyDefault()
    {
        $field = new _abstractFieldTestMock;
        $this->assertObjectHasAttribute('field_name', $field);
        $this->assertEmpty($field->getFieldName());
    }
    
    public function testValDataPropertyDefault()
    {
        $field = new _abstractFieldTestMock;
        $this->assertObjectHasAttribute('val_data', $field);
    }
    
    public function testValDataExtraPropertyDefault()
    {
        $field = new _abstractFieldTestMock;
        $this->assertObjectHasAttribute('val_data_extra', $field);
    }
    
    public function testRulesPropertyDefault()
    {
        $field = new _abstractFieldTestMock;
        $this->assertObjectHasAttribute('rules', $field);
        $this->assertTrue( is_array($field->getRules()) );
        $this->assertCount(0, $field->getRules());
    }
    
    public function testSetContextReturnInstance()
    {
        $field = new _abstractFieldTestMock;
        $settings = new \mithra62\BackupPro\Validate\Settings;
        $this->assertInstanceOf('\mithra62\BackupPro\Validate\AbstractField', $field->setContext($settings));
    }
}