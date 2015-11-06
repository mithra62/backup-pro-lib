<?php
namespace mithra62\BackupPro\tests\Browser;

trait EE3Trait 
{    
    public function login()
    {
        // This is Mink's Session.
        $this->session = $this->getSession();
    
        // Go to a page.
        $this->session->visit('http://eric.ee3.clean.mithra62.com/admin.php?/cp/login');
    
        //log in
        $page = $this->session->getPage();
        $page->findById('username' )->setValue('mithra62');
        $page->findById('password' )->setValue('dimens35');
        $page->findButton('submit')->submit();
    }
    
    protected function setUp()
    {
        $this->login();
        $this->session->visit('http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/div[2]/table/tbody/tr/td[4]/input')->check();
        $action_select = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/select')->selectOption('install');
        $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/button')->submit();
    }
    
    public function  teardown()
    {
        $this->session->visit('http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/div[2]/table/tbody/tr/td[4]/input')->check();
        $action_select = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/select')->selectOption('remove');
        $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/button')->submit();
    }
}
