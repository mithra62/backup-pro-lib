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
    public function testAddFtpStorageNoHost()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_hostname')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Hostname is required'));
    }

    /**
     * @depends testAddFtpStorageBadHost
     */
    public function testAddFtpStorageBadHost()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        $page = $this->session->getPage();
        $page->findById('ftp_hostname')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()->hasContent('Ftp Hostname is required'));
        
        $this->uninstall_addon();
    }
}