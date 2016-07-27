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
use mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\BackupPro\Validate\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class SettingsTest extends TestFixture
{
    protected $existing_settings = array('working_directory' => '');
    
    public function testExistingSettingsProperty()
    {
        $settings = new Settings;
        $this->assertCount(0, $settings->getExistingSettings());
    }
    
    public function testSetExistingSettingsReturnInstance()
    {
        $settings = new Settings;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings', $settings->setExistingSettings(array()));
        $this->assertInstanceOf('mithra62\BackupPro\Validate', $settings->setExistingSettings(array()));
        $this->assertInstanceOf('JaegerApp\Validate', $settings->setExistingSettings(array()));
        $this->assertInstanceOf('Valitron\Validator', $settings->setExistingSettings(array()));
    }
    
    public function testSetExistingSettingsValue()
    {
        $settings = new Settings;
        $this->assertCount(1, $settings->setExistingSettings($this->existing_settings)->getExistingSettings());
    }
    
    public function testSetSqlParserReturnInstance()
    {
        $parser = $this->getMock('\PHPSQL\Parser');
        $settings = new Settings;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings', $settings->setSqlParser($parser));
    }
    
    public function testGetSqlParserReturnValue()
    {
        $parser = $this->getMock('\PHPSQL\Parser');
        $settings = new Settings;
        $settings->setSqlParser($parser);
        $this->assertInstanceOf('\PHPSQL\Parser', $settings->getSqlParser());
    }
    
    public function testSetDbReturnInstance()
    {
        $db = $this->getMock('\JaegerApp\Db');
        $settings = new Settings;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings', $settings->setDb($db));
    }
    
    public function testGetDbReturnValue()
    {
        $db = $this->getMock('\JaegerApp\Db');
        $settings = new Settings;
        $settings->setDb($db);
        $this->assertInstanceOf('\JaegerApp\Db', $settings->getDb());
    }
    
    public function testCheckNoData()
    {
        $data = array();
        $settings = new Settings;
        $this->assertTrue($settings->check($data));
    }
}