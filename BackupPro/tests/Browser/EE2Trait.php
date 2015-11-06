<?php
namespace mithra62\BackupPro\tests\Browser;

trait EE2Trait 
{    
    public function login()
    {
        // This is Mink's Session.
        $this->session = $this->getSession();
    
        // Go to a page.
        $this->session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/login&return=');
    
        //log in
        $page = $this->session->getPage();
        $page->findById('username' )->setValue('mithra62');
        $page->findById('password' )->setValue('dimens35');
        $page->findButton('submit')->submit();
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
        $this->session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons/package_settings&package=backup_pro&return=addons_modules');
        $page = $this->session->getPage();
        $form = $page->findById('install_module')->setValue('install');
        $page->findButton('submit')->submit();
    }
    
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons/package_settings&package=backup_pro&return=addons_modules');
        $page = $this->session->getPage();
        $form = $page->findById('install_module')->setValue('uninstall');
        $page->findButton('submit')->submit();
    }
}
