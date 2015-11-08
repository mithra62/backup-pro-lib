<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/FtpEngine.php
 */

namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage Ftp Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class FtpEngine extends TestFixture  
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
    
    public function testAddFtpStorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
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