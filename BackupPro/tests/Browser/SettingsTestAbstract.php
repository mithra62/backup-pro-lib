<?php

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

class SettingsTestAbstract extends TestFixture  
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
        
        $this->uninstall_addon();
    }

}