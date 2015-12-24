<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/DropboxEngine.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Dropbox Storage Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class DropboxEngine extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var unknown
     */
    public $session = null;

    /**
     * The browser config
     * 
     * @var array
     */
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
            'sessionStrategy' => 'shared'
        )
    );

    public function testAddDropboxStorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddDropboxStorageNoName
     */
    public function testAddDropboxStorageGoodName()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Dropbox Storage');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddDropboxStorageNoName
     */
    public function testAddDropboxStorageAccessTokenNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_access_token')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Dropbox Access Token is required'));
    }

    /**
     * @depends testAddDropboxStorageAccessTokenNoValue
     */
    public function testAddDropboxStorageAccessTokenStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_access_token')->setValue('fdsafdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Dropbox Access Token is required'));
    }

    /**
     * @depends testAddDropboxStorageAccessTokenStringValue
     */
    public function testAddDropboxStorageAppSecretNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_app_secret')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Dropbox App Secret is required'));
    }

    /**
     * @depends testAddDropboxStorageAppSecretNoValue
     */
    public function testAddDropboxStorageAppSecretStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_app_secret')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Dropbox App Secret is required'));
    }

    /**
     * @depends testAddDropboxStorageAppSecretStringValue
     */
    public function testAddDropboxStoragePrefixNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_prefix')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue(($page->findById('dropbox_prefix')
            ->getValue() == ''));
    }

    /**
     * @depends testAddDropboxStoragePrefixNoValue
     */
    public function testAddDropboxStoragePrefixStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        $page = $this->session->getPage();
        $page->findById('dropbox_prefix')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue(($page->findById('dropbox_prefix')
            ->getValue() == 'fdsafdsa'));
    }

    /**
     * @depends testAddDropboxStoragePrefixStringValue
     */
    public function testAddDropboxStorageStatusChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_status')
            ->isChecked());
    }

    /**
     * @depends testAddDropboxStorageStatusChecked
     */
    public function testAddDropboxStorageStatusUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
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
     * @depends testAddDropboxStorageStatusUnChecked
     */
    public function testAddDropboxStorageFileUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_file_use')
            ->isChecked());
    }

    /**
     * @depends testAddDropboxStorageFileUseChecked
     */
    public function testAddDropboxStorageFileUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
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
     * @depends testAddDropboxStorageFileUseUnChecked
     */
    public function testAddDropboxStorageDbUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_db_use')
            ->isChecked());
    }

    /**
     * @depends testAddDropboxStorageDbUseChecked
     */
    public function testAddDropboxStorageDbUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
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
     * @depends testAddDropboxStorageDbUseUnChecked
     */
    public function testAddS3StorageIncludePruneChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddDropboxStorageDbUseUnChecked
     */
    public function testAddDropboxStorageIncludePruneUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddDropboxStorageIncludePruneUnChecked
     */
    public function testAddCompleteDropboxStorage()
    {
        $page = $this->setupDropboxStorageLocation();
        $this->assertTrue($this->session->getPage()
            ->hasContent('Created Date'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
        
        $this->uninstall_addon();
    }
}