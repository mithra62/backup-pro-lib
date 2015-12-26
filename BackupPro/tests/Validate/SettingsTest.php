<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/SettingsTest.php
 */
namespace mithra62\BackupPro\tests\Validate;

use mithra62\tests\TestFixture;
use mithra62\BackupPro\Validate\Settings;

/**
 * mithra62 - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class SettingsTest extends TestFixture
{
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
        $this->assertInstanceOf('mithra62\Validate', $settings->setExistingSettings(array()));
        $this->assertInstanceOf('Valitron\Validator', $settings->setExistingSettings(array()));
    }
    
    public function testSetExistingSettingsValue()
    {
        $settings = new Settings;
        $this->assertCount(2, $settings->setExistingSettings(array('fdsa', 'ff'))->getExistingSettings());
    }
}