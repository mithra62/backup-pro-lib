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
abstract class General extends TestFixture
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

    public function testGeneralViewWorkingDirectoryFieldEmpty()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit($this->url('settings_general'));
        
        $page = $this->session->getPage();
        $page->findById('working_directory')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $page = $this->session->getPage();
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be writable'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be a directory'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be readable'));
    }

    /**
     * @depends testGeneralViewWorkingDirectoryFieldEmpty
     */
    public function testGeneralViewWorkingdirecotryFieldBadPath()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('working_directory')->setValue('fdsafdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Working Directory is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be writable'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be a directory'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Working Directory has to be readable'));
    }

    /**
     * @depends testGeneralViewWorkingdirecotryFieldBadPath
     */
    public function testGeneralViewWorkingdirecotryGoodValue()
    {
        $this->setupGoodWorkingDirectory();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Working Directory is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Working Directory has to be writable'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Working Directory has to be a directory'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Working Directory has to be readable'));
    }

    /**
     * @depends testGeneralViewWorkingdirecotryGoodValue
     */
    public function testGeneralCronQueryKeyEmptyField()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('cron_query_key')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Cron Query Key is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Cron Query Key must be alpha-numeric only'));
    }

    /**
     * @depends testGeneralCronQueryKeyEmptyField
     */
    public function testGeneralCronQueryBadValueField()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('cron_query_key')->setValue('my=bad&key=test');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Cron Query Key is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Cron Query Key must be alpha-numeric only'));
    }

    /**
     * @depends testGeneralCronQueryBadValueField
     */
    public function testGeneralCronQueryGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('cron_query_key')->setValue($this->ts('cron_query_key'));
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Cron Query Key is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Cron Query Key must be alpha-numeric only'));
    }

    /**
     * @depends testGeneralCronQueryGoodValue
     */
    public function testGeneralDashboardRecentTotalEmptyField()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total must be a number greater than 1'));
    }

    /**
     * @depends testGeneralDashboardRecentTotalEmptyField
     */
    public function testGeneralDashboardRecentTotalBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total must be a number greater than 1'));
    }

    /**
     * @depends testGeneralDashboardRecentTotalBadValue
     */
    public function testGeneralDashboardRecentTotalGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total')->setValue($this->ts('dashboard_recent_total'));
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Dashboard Recent Total must be a number greater than 1'));
    }

    /**
     * @depends testGeneralDashboardRecentTotalGoodValue
     */
    public function testGeneralAutoThresholdGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('auto_threshold')->setValue(104857600);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold must be a number'));
    }

    /**
     * @depends testGeneralAutoThresholdGoodValue
     */
    public function testGeneralAutoThresholdCustomEmptyValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('auto_threshold')->selectOption('custom');
        $page->findById('auto_threshold_custom')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be a number'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be at least 100MB (100000000)'));
    }

    /**
     * @depends testGeneralAutoThresholdCustomEmptyValue
     */
    public function testGeneralAutoThresholdCustomStringBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('auto_threshold')->selectOption('custom');
        $page->findById('auto_threshold_custom')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be a number'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be at least 100MB (100000000)'));
    }

    /**
     * @depends testGeneralAutoThresholdCustomStringBadValue
     */
    public function testGeneralAutoThresholdCustomNumberBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('auto_threshold')->selectOption('custom');
        $page->findById('auto_threshold_custom')->setValue(99);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be a number'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be at least 100MB (100000000)'));
    }

    /**
     * @depends testGeneralAutoThresholdCustomStringBadValue
     */
    public function testGeneralAutoThresholdCustomGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('auto_threshold')->selectOption('custom');
        $page->findById('auto_threshold_custom')->setValue(100000000);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be a number'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Auto Threshold Custom must be at least 100MB (100000000)'));
    }

    /**
     * @depends testGeneralAutoThresholdCustomGoodValue
     */
    public function testGeneralAllowDuplicatesCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        
        $page = $this->session->getPage();
        $page->findById('allow_duplicates')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('allow_duplicates')
            ->isChecked());
    }

    /**
     * @depends testGeneralAllowDuplicatesCheck
     */
    public function testGeneralAllowDuplicatesUnCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        
        $page = $this->session->getPage();
        $page->findById('allow_duplicates')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('allow_duplicates')
            ->isChecked());
    }

    /**
     * @depends testGeneralAllowDuplicatesUnCheck
     */
    public function testGeneralDateFormatEmptyValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('date_format')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Date Format is required'));
    }

    /**
     * @depends testGeneralDateFormatEmptyValue
     */
    public function testGeneralDateFormatGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('date_format')->setValue('M d, Y, h:i:sA');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Date Format is required'));
    }

    /**
     * @depends testGeneralDateFormatGoodValue
     */
    public function testGeneralRelativeTimeCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        
        $page = $this->session->getPage();
        $page->findById('relative_time')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('relative_time')
            ->isChecked());
    }

    /**
     * @depends testGeneralRelativeTimeCheck
     */
    public function testGeneralRelativeTimeUnCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        
        $page = $this->session->getPage();
        $page->findById('relative_time')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        //sleep(90);
        $this->assertNotTrue($this->session->getPage()
            ->findById('relative_time')
            ->isChecked());
        $this->uninstall_addon();
    }
}