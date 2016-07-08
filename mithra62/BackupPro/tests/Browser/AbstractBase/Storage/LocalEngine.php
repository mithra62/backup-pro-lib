<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/LocalEngine.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage Local Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class LocalEngine extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var unknown
     */
    public $session = null;

    public function testAddLocalStorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddLocalStorageNoName
     */
    public function testAddLocalStorageGoodName()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Local Storage');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddLocalStorageGoodName
     */
    public function testAddLocalStorageBackupStoreLocationNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        $page = $this->session->getPage();
        $page->findById('backup_store_location')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be a directory'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be writable'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be readable'));
    }

    /**
     * @depends testAddLocalStorageBackupStoreLocationNoValue
     */
    public function testAddLocalStorageBackupStoreLocationBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        $page = $this->session->getPage();
        $page->findById('backup_store_location')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Store Location is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be a directory'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be writable'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be readable'));
    }

    /**
     * @depends testAddLocalStorageBackupStoreLocationNoValue
     */
    public function testAddLocalStorageBackupStoreLocationGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        $page = $this->session->getPage();
        $page->findById('backup_store_location')->setValue(dirname(__FILE__));
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Store Location is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be a directory'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be writable'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Store Location has to be readable'));
    }

    /**
     * @depends testAddLocalStorageBackupStoreLocationGoodValue
     */
    public function testAddLocalStorageLocationStatusChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_status')
            ->isChecked());
    }

    /**
     * @depends testAddLocalStorageLocationStatusChecked
     */
    public function testAddLocalStorageLocationStatusUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_status')
            ->isChecked());
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location Status is required unless you have more than 1 Storage Location'));
    }

    /**
     * @depends testAddLocalStorageLocationStatusUnChecked
     */
    public function testAddLocalStorageLocationFileUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_file_use')
            ->isChecked());
    }

    /**
     * @depends testAddLocalStorageLocationFileUseChecked
     */
    public function testAddLocalStorageLocationFileUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_file_use')
            ->isChecked());
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location File Use is required unless you have more than 1 Storage Location'));
    }

    /**
     * @depends testAddLocalStorageLocationFileUseUnChecked
     */
    public function testAddLocalStorageLocationDbUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_db_use')
            ->isChecked());
    }

    /**
     * @depends testAddLocalStorageLocationDbUseChecked
     */
    public function testAddLocalStorageLocationDbUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_db_use')
            ->isChecked());
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location Db Use is required unless you have more than 1 Storage Location'));
    }

    /**
     * @depends testAddLocalStorageLocationDbUseUnChecked
     */
    public function testAddLocalStorageLocationIncludePruneChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddLocalStorageLocationIncludePruneChecked
     */
    public function testAddLocalStorageLocationIncludePruneUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddLocalStorageLocationIncludePruneUnChecked
     */
    public function testAddCompleteLocalStorage()
    {
        $page = $this->setupLocalStorageLocation($this->ts('local_backup_store_location'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Created Date'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
    }

    /**
     * @depends testAddCompleteLocalStorage
     */
    public function testBackupDatabaseLocalStorage()
    {
        $page = $this->takeDatabaseBackup();
        $this->removeDatabaseBackup();
        $this->uninstall_addon();
    }
}