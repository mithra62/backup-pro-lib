<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/S3Engine.php
 */

namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Amazon S3 Storage Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class S3Engine extends TestFixture  
{   
    
    /**
     * An instance of the mink selenium object
     * @var unknown
     */
    public $session = null;
    
    /**
     * The browser config
     * @var array
     */
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
            'sessionStrategy' => 'shared',
        ),
    );

    public function testAddS3StorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }
    
    /**
     * @depends testAddS3StorageNoName
     */
    public function testAddS3StorageGoodName()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Amazon S3 Storage');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }
    
    /**
     * @depends testAddS3StorageGoodName
     */
    public function testAddS3AccessKeyNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_access_key')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('S3 Access Key is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Can\'t connect to S3 Access Key'));
    }
    
    /**
     * @depends testAddS3AccessKeyNoValue
     */
    public function testAddS3AccessKeyGoodValue()
    {
        $rcf_creds = $this->getS3Creds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_access_key')->setValue( $rcf_creds['s3_access_key'] );
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('S3 Access Key is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Can\'t connect to S3 Access Key'));
    }
    
    /**
     * @depends testAddS3AccessKeyGoodValue
     */
    public function testAddS3SecretKeyNoValue()
    {
        $rcf_creds = $this->getS3Creds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_secret_key')->setValue( '' );
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('S3 Secret Key is required'));
    }
    
    /**
     * @depends testAddS3SecretKeyNoValue
     */
    public function testAddS3SecretKeyGoodValue()
    {
        $rcf_creds = $this->getS3Creds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_secret_key')->setValue( $rcf_creds['s3_secret_key'] );
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('S3 Secret Key is required'));
    }
    
    /**
     * @depends testAddS3SecretKeyGoodValue
     */
    public function testAddS3BucketNoValue()
    {
        $rcf_creds = $this->getS3Creds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_bucket')->setValue( '' );
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('S3 Bucket is required'));
    }
    
    /**
     * @depends testAddS3BucketNoValue
     */
    public function testAddS3BucketGoodValue()
    {
        $rcf_creds = $this->getS3Creds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
        $page = $this->session->getPage();
        $page->findById('s3_bucket')->setValue( $rcf_creds['s3_bucket'] );
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('S3 Bucket is required'));
    }
    
    
    
    
    
    /**
     * @depends testAddS3BucketGoodValue
     */
    public function testAddS3StorageStatusChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }
    
    /**
     * @depends testAddS3StorageStatusChecked
     */
    public function testAddS3StorageStatusUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Status is required unless you have more than 1 Storage Location'));
    }
    
    /**
     * @depends testAddS3StorageStatusUnChecked
     */
    public function testAddS3StorageFileUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }
    
    /**
     * @depends testAddS3StorageFileUseChecked
     */
    public function testAddS3StorageFileUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location File Use is required unless you have more than 1 Storage Location'));
    }
    
    /**
     * @depends testAddS3StorageFileUseUnChecked
     */
    public function testAddS3StorageDbUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }
    
    /**
     * @depends testAddS3StorageDbUseChecked
     */
    public function testAddS3StorageDbUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Db Use is required unless you have more than 1 Storage Location'));
    }
    
    /**
     * @depends testAddS3StorageDbUseUnChecked
     */
    public function testAddS3StorageIncludePruneChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddS3StorageIncludePruneChecked
     */
    public function testAddS3StorageIncludePruneUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_s3storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddS3StorageIncludePruneUnChecked
     */
    public function testAddCompleteS3Storage()
    {
        $page = $this->setupS3StorageLocation( );
        $this->assertTrue($this->session->getPage()->hasContent('Created Date'));
        $this->assertNotTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
        
        $this->uninstall_addon();
    }
    
}