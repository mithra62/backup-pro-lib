<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Backup.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Backup Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class Backup extends TestFixture
{
    /**
     * An instance of the mink selenium object
     *
     * @var unknown
     */
    public $session = null;
    
    public function testPhpDatabaseBackup()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
    
        $page = $this->setupLocalStorageLocation($this->ts('local_backup_store_location'));
        
        $this->session->visit($this->url('settings_db'));
        $page->findById('db_backup_method')->selectOption('php');
        sleep(1);
        $page->findButton('m62_settings_submit')->submit();
        
        $page = $this->takeDatabaseBackup();
        
        $this->session->visit($this->url('db_backups'));
        
        $page = $this->session->getPage();
        $this->assertTrue($page->hasField('backup_check_0'));
        
        $this->removeDatabaseBackup();
        
        $this->session->visit($this->url('db_backups'));
        $page = $this->session->getPage();
        $this->assertFalse($page->hasField('backup_check_0'));
        
    }
    
    /**
     * @depends testPhpDatabaseBackup
     */
    public function testMysqldumpBackup()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('mysqldump');
        sleep(1);
        $page->findButton('m62_settings_submit')->submit();
        
        $page = $this->takeDatabaseBackup();
        
        $this->session->visit($this->url('db_backups'));
        
        $page = $this->session->getPage();
        $this->assertTrue($page->hasField('backup_check_0'));
        
        $this->removeDatabaseBackup();
        
        $this->session->visit($this->url('db_backups'));
        $page = $this->session->getPage();
        $this->assertFalse($page->hasField('backup_check_0'));

        $this->uninstall_addon();
    }
}