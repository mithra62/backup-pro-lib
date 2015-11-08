<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Settings/License.php
 */

namespace mithra62\BackupPro\tests\Browser\AbstractBase\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Cron Backup File object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class License extends TestFixture  
{   
    
    /**
     * An instance of the mink selenium object
     * @var unknown
     */
    public $session = null;
    
    /**
     * The browser config
     * @var array
     */
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
            'sessionStrategy' => 'shared',
        ),
    );
    
    public function testLicenseKeyNoValue()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_license') );
        $page = $this->session->getPage();
        $page->findById('license_number')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('License Number is required'));
        $this->assertTrue($this->session->getPage()->hasContent('License Number isn\'t a valid license key'));
    }
    
    /**
     * @depends testLicenseKeyNoValue
     */
    public function testLicenseKeyBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_license') );
        $page = $this->session->getPage();
        $page->findById('license_number')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('License Number is required'));
        $this->assertTrue($this->session->getPage()->hasContent('License Number isn\'t a valid license key'));
    }
    
    /**
     * @depends testLicenseKeyBadValue
     */
    public function testLicenseKeyGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_license') );
        $page = $this->session->getPage();
        $page->findById('license_number')->setValue('5214af45-9bc9-4019-8af9-bc98c38802c1');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('License Number is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('License Number isn\'t a valid license key'));
        $this->uninstall_addon();
    }
}