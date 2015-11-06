<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/EE2Trait.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

/**
 * mithra62 - ExpressionEngine 2 Trait
 *
 * Contains all the methods for using Selenium against Backup Pro and ExpressionEngine 2
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait EE2Trait 
{    
    /**
     * Logs the user into the ExpressionEngine 2 site
     */
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
    
    /**
     * Helper method to handle logging in and installing the add-on
     */
    protected function setUp()
    {
        $this->login();
        $this->install_addon();
    }
    
    /**
     * Helper method to handle uinstalling the add-on
     */
    public function  teardown()
    {
        $this->uninstall_addon();
    }
    
    /**
     * Installs the add-on
     */
    public function install_addon()
    {
        $this->session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons/package_settings&package=backup_pro&return=addons_modules');
        $page = $this->session->getPage();
        $form = $page->findById('install_module')->setValue('install');
        $page->findButton('submit')->submit();
    }
    
    /**
     * Uninstalls the add-on
     */
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons/package_settings&package=backup_pro&return=addons_modules');
        $page = $this->session->getPage();
        $form = $page->findById('install_module')->setValue('uninstall');
        $page->findButton('submit')->submit();
    }
}
