<?php
namespace mithra62\BackupPro\tests\Browser;

use aik099\PHPUnit\BrowserTestCase;
use mithra62\tests\TestTrait;

class TestFixture extends BrowserTestCase
{
    use TestTrait; 
    
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
        ),
    );
    
    protected function url($key)
    {
        return $this->urls[$key];
    }
    
    protected function ts($key)
    {
        return $this->test_settings[$key];
    }
    
    public function iDisableTheAlerts()
    {
       $javascript = "window.confirm = function() {};";
       $this->session->executeScript($javascript);
    }
    
    protected function setupWorkingDirSetting($path)
    {
        
    }
}