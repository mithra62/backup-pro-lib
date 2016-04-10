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

use mithra62\tests\TestFixture;
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
        $this->assertInstanceOf('mithra62\Validate', $settings->setExistingSettings(array()));
        $this->assertInstanceOf('Valitron\Validator', $settings->setExistingSettings(array()));
    }
    
    public function testSetExistingSettingsValue()
    {
        $settings = new Settings;
        $this->assertCount(1, $settings->setExistingSettings($this->existing_settings)->getExistingSettings());
    }
    
    /*
    
    public function testWorkingDirectoryReturnInstance()
    {
        $settings = new Settings;
        $this->assertInstanceOf('mithra62\BackupPro\Validate\Settings', $settings->setExistingSettings($this->existing_settings)->workingDirectory(__DIR__));
    }
    
    public function testWorkingDirectoryBadValue()
    {
        $settings = new Settings;
        $settings->setExistingSettings($this->existing_settings)->workingDirectory($this->getWorkingDir())->val(array('working_dir' => 'fdsa'));
        $this->assertTrue($settings->hasErrors());
        
        $errors = $settings->getErrorMessages();
        $this->assertArrayHasKey('working_directory', $errors);
        $this->assertCount(5, $errors['working_directory']);
    }
    
    public function testWorkingDirectoryDirContentValue()
    {
        $settings = new Settings;
        $settings->setExistingSettings($this->existing_settings)->workingDirectory($this->getWorkingDir())->val(array('working_directory' => __DIR__));
        $this->assertTrue($settings->hasErrors());
        
        $errors = $settings->getErrorMessages();
        $this->assertArrayHasKey('working_directory', $errors);
        $this->assertCount(1, $errors['working_directory']);
    }
    
    public function testWorkingDirectoryDirGoodValue()
    {
        $settings = new Settings;
        $settings->setExistingSettings($this->existing_settings)->workingDirectory($this->getWorkingDir())->val(array('working_directory' => $this->getWorkingDir()));
        $this->assertNotTrue($settings->hasErrors());
    }
    
    public function testWorkingDirectoryExistingSettingBadValue()
    {
        $settings = new Settings;
        $storage_details = array(
            'working_directory' => '',
            'storage_details' => array(array(
                'storage_location_driver' => 'local', 
                'backup_store_location' => $this->getWorkingDir()
            ) )
        );
        $settings->setExistingSettings($storage_details);
        $settings->workingDirectory($this->getWorkingDir())->val(array('working_directory' => $this->getWorkingDir()));
        
        $errors = $settings->getErrorMessages();
        $this->assertCount(1, $errors['working_directory']);
    }
    
    public function testWorkingDirectoryExistingSettingGoodValue()
    {
        $settings = new Settings;
        $storage_details = array(
            'working_directory' => '',
            'storage_details' => array(array(
                'storage_location_driver' => 'local', 
                'backup_store_location' => __DIR__
            ) )
        );
        $settings->setExistingSettings($storage_details);
        $settings->workingDirectory($this->getWorkingDir())->val(array('working_directory' => $this->getWorkingDir()));
        
        $errors = $settings->getErrorMessages();
        $this->assertCount(0, $errors);
    }
    
    public function testDateFormatGoodValue()
    {
        $settings = new Settings;
        $settings->dateFormat()->val(array('date_format' => "M d, Y, h:i:sA"));

        $errors = $settings->getErrorMessages();
        $this->assertCount(0, $errors);        
    }
    
    public function testDateFormatNoValue()
    {
        $settings = new Settings;
        $settings->dateFormat()->val(array('date_format' => ""));

        $errors = $settings->getErrorMessages();
        $this->assertCount(1, $errors);        
    }
    */
}