<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/WpTrait.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

/**
 * mithra62 - WordPress Trait
 *
 * Contains all the methods for using Selenium against Backup Pro and WordPress
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait WpTrait 
{    
    /**
     * Logs the user into the WordPress site
     */
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
        $this->session->visit('http://eric.wp.clean.mithra62.com/wp-admin/plugins.php');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/table/tbody/tr[2]/th/input')->check();
        $action_select = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/div[2]/div[1]/select')->selectOption('activate-selected');
        $page->findButton('doaction2')->submit();
    }
    
    /**
     * Uninstalls the add-on
     */
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.wp.clean.mithra62.com/wp-admin/plugins.php');
        $page = $this->session->getPage();
        $checkbox = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/table/tbody/tr[2]/th/input')->check();
        $action_select = $page->find('xpath', '/body/div[1]/div[2]/div[2]/div[1]/div[3]/form[2]/div[2]/div[1]/select')->selectOption('deactivate-selected');
        $page->findButton('doaction2')->submit();
    }
    
}
