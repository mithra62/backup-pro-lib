<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/StorageTestAbstract.php
 */

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class StorageTestAbstract extends TestFixture  
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
    
    public function testNoStorageLocationsCreatedYet()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit( $this->url('storage_view_storage') );
        
        $page = $this->session->getPage();
        
        $page = $this->session->getPage();
        $this->assertTrue($this->session->getPage()->hasContent('No Storage Locations Created Yet'));
    }

    /**
     * @depends testNoStorageLocationsCreatedYet
     */
    public function testAddEmailStorageNoName() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddEmailStorageNoName
     */
    public function testAddEmailStorageGoodName() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Email Storage');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddEmailStorageGoodName
     */
    public function testAddEmailStorageNoEmailAddresses() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('email_storage_emails')->setValue('');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertTrue($this->session->getPage()->hasContent('Email Storage Emails is required'));
    }

    /**
     * @depends testAddEmailStorageNoEmailAddresses
     */
    public function testAddEmailStorageBadEmailAddresses() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('email_storage_emails')->setValue("fdsafdsa\neric@mithra62.com\nuuuuuuuu");
        $page->findButton('m62_settings_submit')->submit();


        $this->assertTrue($this->session->getPage()->hasContent('"fdsafdsa" isn\'t a valid email'));
        $this->assertTrue($this->session->getPage()->hasContent('"uuuuuuuu" isn\'t a valid email'));
        $this->assertNotTrue($this->session->getPage()->hasContent('"eric@mithra62.com" isn\'t a valid email'));
    }

    /**
     * @depends testAddEmailStorageBadEmailAddresses
     */
    public function testAddEmailStorageAttachMaxSizeNoValue() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('email_storage_attach_threshold')->setValue('');
        $page->findButton('m62_settings_submit')->submit();


        $this->assertTrue($this->session->getPage()->hasContent('Email Storage Attach Threshold is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Email Storage Attach Threshold must be a number'));
    }

    /**
     * @depends testAddEmailStorageAttachMaxSizeNoValue
     */
    public function testAddEmailStorageAttachMaxSizeStringValue() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('email_storage_attach_threshold')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();


        $this->assertNotTrue($this->session->getPage()->hasContent('Email Storage Attach Threshold is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Email Storage Attach Threshold must be a number'));
    }

    /**
     * @depends testAddEmailStorageAttachMaxSizeStringValue
     */
    public function testAddEmailStorageLocationStatusChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationStatusChecked
     */
    public function testAddEmailStorageLocationStatusUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationStatusUnChecked
     */
    public function testAddEmailStorageLocationFileUseChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationFileUseChecked
     */
    public function testAddEmailStorageLocationFileUseUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationFileUseUnChecked
     */
    public function testAddEmailStorageLocationDbUseChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationDbUseChecked
     */
    public function testAddEmailStorageLocationDbUseUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationDbUseUnChecked
     */
    public function testAddEmailStorageLocationIncludePruneChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }

    /**
     * @depends testAddEmailStorageLocationIncludePruneChecked
     */
    public function testAddEmailStorageLocationIncludePruneUnChecked()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddEmailStorageLocationIncludePruneUnChecked
     */
    public function testAddCompleteEmailStorage()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('Test Email Storage');
        $page->findById('email_storage_attach_threshold')->setValue('0');
        $page->findById('email_storage_emails')->setValue('eric@mithra62.com');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Added!'));
        $this->assertTrue($this->session->getPage()->hasContent('Test Email Storage'));
        $this->assertNotTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
    }

    /**
     * @depends testAddCompleteEmailStorage
     */
    public function testAddFtpStorageNoName()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddFtpStorageNoName
     */
    public function testAddFtpStorageGoodName() 
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My FTP Storage');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }

    /**
     * @depends testAddFtpStorageGoodName
     */
    public function testAddFtpStorageNoHostValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_hostname')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Hostname is required'));
    }

    /**
     * @depends testAddFtpStorageNoHostValue
     */
    public function testAddFtpStorageGoodHostValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_hostname')->setValue($ftp_creds['ftp_hostname']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Hostname is required'));
    }

    /**
     * @depends testAddFtpStorageGoodHostValue
     */
    public function testAddFtpStorageUserNameNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_username')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Username is required'));
    }

    /**
     * @depends testAddFtpStorageUserNameNoValue
     */
    public function testAddFtpStorageUserNameGoodValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_username')->setValue($ftp_creds['ftp_username']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Username is required'));
    }

    /**
     * @depends testAddFtpStorageUserNameGoodValue
     */
    public function testAddFtpStoragePasswordNoValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_hostname')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Password is required'));
    }

    /**
     * @depends testAddFtpStoragePasswordNoValue
     */
    public function testAddFtpStoragePasswordGoodValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_password')->setValue($ftp_creds['ftp_password']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Password is required'));
    }

    /**
     * @depends testAddFtpStoragePasswordGoodValue
     */
    public function testAddFtpStoragePortNoValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_port')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Port is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Port must be a number'));
    }

    /**
     * @depends testAddFtpStoragePortNoValue
     */
    public function testAddFtpStoragePortBadValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_port')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Port is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Port must be a number'));
    }

    /**
     * @depends testAddFtpStoragePortBadValue
     */
    public function testAddFtpStoragePortGoodValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_port')->setValue($ftp_creds['ftp_port']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Port is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Port must be a number'));
    }

    /**
     * @depends testAddFtpStoragePortGoodValue
     */
    public function testAddFtpStorageLocationPathNoValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_store_location')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Store Location is required'));
    }

    /**
     * @depends testAddFtpStorageLocationPathNoValue
     */
    public function testAddFtpStorageLocationPathGoodValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('ftp_store_location')->setValue($ftp_creds['ftp_store_location']);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Store Location is required'));
    }

    /**
     * @depends testAddFtpStorageLocationPathNoValue
     */
    public function testAddFtpStoragePassiveModeChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_passive')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('ftp_passive')->isChecked());
    }

    /**
     * @depends testAddFtpStoragePassiveModeChecked
     */
    public function testAddFtpStoragePassiveModeUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_passive')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('ftp_passive')->isChecked());
    }

    /**
     * @depends testAddFtpStoragePassiveModeUnChecked
     */
    public function testAddFtpStorageUseSSLChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_ssl')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('ftp_ssl')->isChecked());
    }

    /**
     * @depends testAddFtpStorageUseSSLChecked
     */
    public function testAddFtpStorageUseSSLUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_ssl')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('ftp_ssl')->isChecked());
    }

    /**
     * @depends testAddFtpStorageUseSSLUnChecked
     */
    public function testAddFtpStorageConnectionTimeoutNoValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_timeout')->setValue('');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertTrue($this->session->getPage()->hasContent('Ftp Timeout is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Timeout must be a number'));
    }

    /**
     * @depends testAddFtpStorageConnectionTimeoutNoValue
     */
    public function testAddFtpStorageConnectionTimeoutStringValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_timeout')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Timeout is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Timeout must be a number'));
    }

    /**
     * @depends testAddFtpStorageConnectionTimeoutStringValue
     */
    public function testAddFtpStorageConnectionTimeoutGoodValue()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('ftp_timeout')->setValue('30');
        $page->findButton('m62_settings_submit')->submit();

        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Timeout is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Ftp Timeout must be a number'));
    }

    /**
     * @depends testAddFtpStorageConnectionTimeoutGoodValue
     */
    public function testAddFtpStorageLocationStatusChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationStatusChecked
     */
    public function testAddFtpStorageLocationStatusUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationStatusUnChecked
     */
    public function testAddFtpStorageLocationFileUseChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationFileUseChecked
     */
    public function testAddFtpStorageLocationFileUseUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationFileUseUnChecked
     */
    public function testAddFtpStorageLocationDbUseChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationDbUseChecked
     */
    public function testAddFtpStorageLocationDbUseUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationDbUseUnChecked
     */
    public function testAddFtpStorageLocationIncludePruneChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }

    /**
     * @depends testAddFtpStorageLocationIncludePruneChecked
     */
    public function testAddFtpStorageLocationIncludePruneUnChecked()
    {
        $ftp_creds = $this->getFtpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
        $this->uninstall_addon();
    }
}