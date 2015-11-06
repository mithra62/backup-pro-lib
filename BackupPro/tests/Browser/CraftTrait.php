<?php
namespace mithra62\BackupPro\tests\Browser;

trait CraftTrait 
{    
    public function login()
    {
        // This is Mink's Session.
        $this->session = $this->getSession();
    
        // Go to a page.
        $this->session->visit('http://eric.craft.clean.mithra62.com/admin/login');
    
        //log in
        $page = $this->session->getPage();
        $page->findById('loginName' )->setValue('mithra62');
        $page->findById('password' )->setValue('dimens35');
        $page->findButton('submit')->submit();
    }
    
    protected function setUp()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
    }
    
    public function  teardown()
    {
        $this->uninstall_addon();
    }
    
    public function install_addon()
    {
        $this->session->visit('http://eric.craft.clean.mithra62.com/admin/settings/plugins');
        sleep(2);
        $page = $this->session->getPage();
        $form = $page->find('xpath', '/body/div/main/div/div/div/div/div/table/tbody/tr/td[2]/form/input[2]')->click();
    }
    
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.craft.clean.mithra62.com/admin/settings/plugins');
        sleep(2);
        $page = $this->session->getPage();
        $form = $page->find('xpath', '/body/div/main/div/div/div/div/div/table/tbody/tr/td[2]/form/input[2]')->click();
    }
}
