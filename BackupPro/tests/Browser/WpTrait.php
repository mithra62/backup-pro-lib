<?php
namespace mithra62\BackupPro\tests\Browser;

trait WpTrait 
{    
    public function login()
    {
        // This is Mink's Session.
        $this->session = $this->getSession();
    
        // Go to a page.
        $this->session->visit('http://eric.wp.clean.mithra62.com/wp-login.php');
    
        //log in
        $page = $this->session->getPage();
        $page->findById('user_login' )->setValue('mithra62');
        $page->findById('user_pass' )->setValue('dimens35');
        $page->findButton('wp-submit')->submit();
    }
    
    protected function setUp()
    {
        $this->login();
        $this->install_addon();
    }
    
    public function  teardown()
    {
        $this->uninstall_addon();
    }
    
    public function install_addon()
    {
        $this->session->visit('http://eric.wp.clean.mithra62.com/wp-admin/plugins.php');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/table/tbody/tr[2]/th/input')->check();
        $action_select = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/div[2]/div[1]/select')->selectOption('activate-selected');
        $page->findButton('doaction2')->submit();
    }
    
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.wp.clean.mithra62.com/wp-admin/plugins.php');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/table/tbody/tr[2]/th/input')->check();
        $action_select = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/div[2]/div[1]/select')->selectOption('deactivate-selected');
        $page->findButton('doaction2')->submit();
    }
    
}
