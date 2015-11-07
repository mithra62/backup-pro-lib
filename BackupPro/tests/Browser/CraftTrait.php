<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/CraftTrait.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

use mithra62\Db;

/**
 * mithra62 - Craft Trait
 *
 * Contains all the methods for using Selenium against Backup Pro and Craft
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait CraftTrait 
{    
    /**
     * Logs the user into the Craft site
     */
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
    
    /**
     * Helper method to handle logging in and installing the add-on
     */
    protected function setUp()
    {
        $this->login();
        sleep(2);
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
        $this->session->visit('http://eric.craft.clean.mithra62.com/admin/settings/plugins');
        sleep(2);
        $page = $this->session->getPage();
        $form = $page->find('xpath', '/body/div/main/div/div/div/div/div/table/tbody/tr/td[2]/form/input[2]')->click();
    }
    
    /**
     * Uninstalls the add-on
     */
    public function uninstall_addon()
    {
        $this->session->visit('http://eric.craft.clean.mithra62.com/admin/settings/plugins');
        sleep(2);
        $page = $this->session->getPage();
        $form = $page->find('xpath', '/body/div/main/div/div/div/div/div/table/tbody/tr/td[2]/form/input[2]')->click();
        
        $db = new Db();
        $creds = $this->getDbCreds();
        $db->setCredentials( $creds )->setDbName('clean_craft')->emptyTable('craft_backup_pro_settings');
        $db->setDbName($creds['database']);
    }
}
