<?php

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

abstract class SettingsTestAbstract extends TestFixture  
{   
    public $session = null;
    
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
    
    public function testGeneralViewWorkingDirectoryFieldEmpty()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit( $this->url('settings_general') );
        
        $page = $this->session->getPage();
        $page->findById('working_directory' )->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $page = $this->session->getPage();
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be writable'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be a directory'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be readable'));
    }
    
    /**
     * @depends testGeneralViewWorkingDirectoryFieldEmpty
     */
    public function testGeneralViewWorkingdirecotryFieldBadPath()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('working_directory' )->setValue('fdsafdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Working Directory is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be writable'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be a directory'));
        $this->assertTrue($this->session->getPage()->hasContent('Working Directory has to be readable'));
    }
    
    /**
     * @depends testGeneralViewWorkingdirecotryFieldBadPath
     */
    public function testGeneralViewWorkingdirecotryGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('working_directory' )->setValue( $this->ts('working_directory') );
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Working Directory is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Working Directory has to be writable'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Working Directory has to be a directory'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Working Directory has to be readable'));
    }
    
    /**
     * @depends testGeneralViewWorkingdirecotryGoodValue
     */
    public function testGeneralCronQueryKeyEmptyField()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('cron_query_key' )->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Cron Query Key is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Cron Query Key must be alpha-numeric only'));
    }
    
    /**
     * @depends testGeneralCronQueryKeyEmptyField
     */
    public function testGeneralCronQueryBadValueField()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('cron_query_key' )->setValue('my=bad&key=test');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Cron Query Key is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Cron Query Key must be alpha-numeric only'));
    }
    
    /**
     * @depends testGeneralCronQueryBadValueField
     */
    public function testGeneralCronQueryGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('cron_query_key' )->setValue( $this->ts('cron_query_key') );
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Cron Query Key is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Cron Query Key must be alpha-numeric only'));
    }

    /**
     * @depends testGeneralCronQueryGoodValue
     */
    public function testGeneralDashboardrRecentTotalEmptyField()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total' )->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Dashboard Recent Total is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Dashboard Recent Total must be a number greater than 1'));
    }

    /**
     * @depends testGeneralDashboardrRecentTotalEmptyField
     */
    public function testGeneralDashboardrRecentTotalBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total' )->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Dashboard Recent Total is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Dashboard Recent Total must be a number greater than 1'));
    }

    /**
     * @depends testGeneralDashboardrRecentTotalBadValue
     */
    public function testGeneralDashboardrRecentTotalGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('settings_general') );
        $page = $this->session->getPage();
        $page->findById('dashboard_recent_total' )->setValue( $this->ts('dashboard_recent_total') );
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Dashboard Recent Total is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Dashboard Recent Total must be a number greater than 1'));
    
        $this->uninstall_addon();
    }

}