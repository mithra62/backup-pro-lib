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
use mithra62\BackupPro\Validate\Settings\BackupFileLocation AS Field;

/**
 * Backup Pro - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\BackupPro\Validate\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class BackupFileLocationTest extends TestFixture
{
    protected $expected_rules = array(
        'false', 
        'required',
    );
    
    public function testObjectInstance()
    {
        $field = new Field;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\AbstractField', $field);
    }
    
    public function testFieldNameValue()
    {
        $field = new Field;
        $this->assertEquals('backup_file_location', $field->getFieldName());
    }
    
    /**
    public function testCompileRulesReturnInstance()
    {
        $field = new Field;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings\BackupFileLocation', $field->compileRules());
    }
    
    
    public function testCompiledRules()
    {
        $field = new Field;
        $field->compileRules();
        $rules = $field->getRuleNames();
        $this->assertCount(1, $rules['backup_file_location']);
    }
    
    public function testEmptyValueReturnErrors()
    {
        $field = new Field(array('backup_file_location' => ''));
        $field->compileRules();
        $rules = $field->getRuleNames();
        
        $this->assertCount(1, $rules['backup_file_location']);
        $this->assertArrayHasKey('required', $rules['backup_file_location']);
    }
    
    public function testGoodPathReturnErrors()
    {
        $field = new Field(array('backup_file_location' => __DIR__));
        $field->compileRules();
        $rules = $field->getRuleNames();
        $this->assertCount(0, $rules);
    }
    
    public function testBadPathReturnErrors()
    {
        $field = new Field(array('backup_file_location' => 'fdsafdsa'));
        $field->compileRules();
        $rules = $field->getRuleNames();
        $this->assertCount(1, $rules['backup_file_location']);
        $this->assertArrayHasKey('false', $rules['backup_file_location']);
    }
    
    */
}
