<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/GcsEngine.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage Google Cloud Storage Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class GcsEngine extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var \Behat\Mink\Session
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

    public function testAddGcsStorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddGcsStorageNoName
     */
    public function testAddGcsStorageGoodName()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My GCS Storage');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddGcsStorageGoodName
     */
    public function testAddGcsAccessKeyIdNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_access_key')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Gcs Access Key is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Can\'t connect to Gcs Access Key'));
    }

    /**
     * @depends testAddGcsAccessKeyIdNoValue
     */
    public function testAddGcsAccessKeyIdGoodValue()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_access_key')->setValue($gcs_creds['gcs_access_key']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Gcs Access Key is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Can\'t connect to Gcs Access Key'));
    }

    /**
     * @depends testAddGcsAccessKeyIdGoodValue
     */
    public function testAddGcsAccessKeySecretNoValue()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_secret_key')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Gcs Secret Key is required'));
    }

    /**
     * @depends testAddGcsAccessKeySecretNoValue
     */
    public function testAddGcsAccessKeySecretGoodValue()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_secret_key')->setValue($gcs_creds['gcs_secret_key']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Gcs Secret Key is required'));
    }

    /**
     * @depends testAddGcsAccessKeySecretGoodValue
     */
    public function testAddGcsBucketNoValue()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_bucket')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Gcs Bucket is required'));
    }

    /**
     * @depends testAddGcsBucketNoValue
     */
    public function testAddGcsBucketGoodValue()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        $page = $this->session->getPage();
        $page->findById('gcs_bucket')->setValue($gcs_creds['gcs_bucket']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Gcs Bucket is required'));
    }

    /**
     * @depends testAddGcsBucketGoodValue
     */
    public function testAddGcsStorageLocationStatusChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_status')
            ->isChecked());
    }

    /**
     * @depends testAddGcsStorageLocationStatusChecked
     */
    public function testAddGcsStorageLocationStatusUnChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
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
     * @depends testAddGcsStorageLocationStatusUnChecked
     */
    public function testAddGcsStorageLocationFileUseChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_file_use')
            ->isChecked());
    }

    /**
     * @depends testAddGcsStorageLocationFileUseChecked
     */
    public function testAddGcsStorageLocationFileUseUnChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
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
     * @depends testAddGcsStorageLocationFileUseUnChecked
     */
    public function testAddGcsStorageLocationDbUseChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_db_use')
            ->isChecked());
    }

    /**
     * @depends testAddGcsStorageLocationDbUseChecked
     */
    public function testAddGcsStorageLocationDbUseUnChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
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
     * @depends testAddGcsStorageLocationDbUseUnChecked
     */
    public function testAddGcsStorageLocationIncludePruneChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddGcsStorageLocationIncludePruneChecked
     */
    public function testAddGcsStorageLocationIncludePruneUnChecked()
    {
        $ftp_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('storage_location_include_prune')
            ->isChecked());
    }

    /**
     * @depends testAddGcsStorageLocationIncludePruneUnChecked
     */
    public function testAddGcsStorageLocationCompleteWorking()
    {
        $page = $this->setupGcsStorageLocation();
        $this->assertTrue($this->session->getPage()
            ->hasContent('Created Date'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
    }

    /**
     * @depends testAddGcsStorageLocationCompleteWorking
     */
    public function testBackupDatabaseGcsStorage()
    {
        $page = $this->takeDatabaseBackup();
        $this->removeDatabaseBackup();
        $this->uninstall_addon();
    }
}