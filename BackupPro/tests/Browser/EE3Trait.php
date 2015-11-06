<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/EE3Trait.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

/**
 * mithra62 - ExpressionEngine 3 Trait
 *
 * Contains all the methods for using Selenium against Backup Pro and ExpressionEngine 3
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait EE3Trait 
{    
    /**
     * Logs the user into the ExpressionEngine 3 site
     */
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
        $this->session->visit('http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/div[2]/table/tbody/tr/td[4]/input')->check();
        $action_select = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/select')->selectOption('install');
        $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/button')->submit();
    }
    
    /**
     * Uninstalls the add-on
     */
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/div[2]/table/tbody/tr/td[4]/input')->check();
        $action_select = $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/select')->selectOption('remove');
        $page->find('xpath', '/body/section[3]/div[2]/div/div[2]/div/form/fieldset/button')->submit();
    }
}
