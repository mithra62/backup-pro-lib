<?php
namespace mithra62\BackupPro\tests\Browser;

use aik099\PHPUnit\BrowserTestCase;

class TestFixture extends BrowserTestCase
{
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
        ),
        array(
            'driver' => 'goutte',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
        ),
    );
    
    public $urls = array();
    
    public function login()
    {
        // This is Mink's Session.
        $session = $this->getSession();
    
        // Go to a page.
        $session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/login&return=');
    
        //log in
        $page = $session->getPage();
        $page->findById('username' )->setValue('mithra62');
        $page->findById('password' )->setValue('dimens35');
        $page->findButton('submit')->submit();
    
        return $session;
    }
    
    protected function url($key)
    {
        return $this->urls[$key];
    }
}