<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/General.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Settings object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class RestApi extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var unknown
     */
    public $session = null;

    /**
     * The browser config
     * 
     * @var array
     */
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
            'sessionStrategy' => 'shared'
        )
    );

    public function testEnableRestApiCheck()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        $this->setGoodRestApi();

        $this->session->visit($this->url('settings_api'));
        $this->assertTrue($this->session->getPage()
            ->findById('enable_rest_api')
            ->isChecked());
    }
    
    /**
     * @depends testEnableRestApiCheck
     */
    public function testGeneralAllowDuplicatesUnCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
    
        $page = $this->session->getPage();
        $page->findById('enable_rest_api')->uncheck();
        $page->findButton('m62_settings_submit')->submit();

        $this->session->visit($this->url('settings_api'));
        $this->assertNotTrue($this->session->getPage()
            ->findById('enable_rest_api')
            ->isChecked());
        
        $this->uninstall_addon();
    }   
}