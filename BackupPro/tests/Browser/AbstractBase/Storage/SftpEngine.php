<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/SftpEngine.php
 */

namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage Sftp Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class SftpEngine extends TestFixture  
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
    
    public function testAddSftpStorageNoName()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }
    
    /**
     * @depends testAddSftpStorageNoName
     */
    public function testAddSftpStorageGoodName()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My FTP Storage');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Storage Location Name is required'));
    }
    
    /**
     * @depends testAddSftpStorageGoodName
     */
    public function testAddSftpStorageNoHostValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_host')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Host is required'));
    }
    
    /**
     * @depends testAddSftpStorageNoHostValue
     */
    public function testAddSftpStorageGoodHostValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_host')->setValue($ftp_creds['sftp_host']);
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Host is required'));
    }
    
    /**
     * @depends testAddSftpStorageGoodHostValue
     */
    public function testAddSftpStorageUserNameNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_username')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Username is required'));
    }
    
    /**
     * @depends testAddSftpStorageUserNameNoValue
     */
    public function testAddSftpStorageUserNameGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_username')->setValue($ftp_creds['sftp_username']);
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Username is required'));
    }
    
    /**
     * @depends testAddSftpStorageUserNameGoodValue
     */
    public function testAddSftpStoragePasswordNoValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_password')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('A password is required if no private key is set'));
    }
    
    /**
     * @depends testAddSftpStoragePasswordNoValue
     */
    public function testAddSftpStoragePasswordGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_password')->setValue($ftp_creds['sftp_password']);
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('A password is required if no private key is set'));
    }
    
    /**
     * @depends testAddSftpStoragePasswordGoodValue
     */
    public function testAddSftpPrivateKeyNoValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_private_key')->setValue('');
        $page->findById('sftp_password')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('A private key is required if no password is set'));
    }
    
    /**
     * @depends testAddSftpPrivateKeyNoValue
     */
    public function testAddSftpPrivateKeyGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
        $page = $this->session->getPage();
        $page->findById('sftp_private_key')->setValue('/fdsafdsa/fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('A private key is required if no password is set'));
    }
    
    /**
     * @depends testAddSftpPrivateKeyGoodValue
     */
    public function testAddSftpStoragePortNoValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_port')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Port is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Port must be a number'));
    }
    
    /**
     * @depends testAddSftpStoragePortNoValue
     */
    public function testAddSftpStoragePortBadValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_port')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Port is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Port must be a number'));
    }
    
    /**
     * @depends testAddSftpStoragePortBadValue
     */
    public function testAddSftpStoragePortGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_port')->setValue($ftp_creds['sftp_port']);
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Port is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Port must be a number'));
    }
    
    /**
     * @depends testAddSftpStoragePortGoodValue
     */
    public function testAddSftpStorageRootNoValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_root')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Root is required'));
    }
    
    /**
     * @depends testAddSftpStorageRootNoValue
     */
    public function testAddSftpStorageRootGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_root')->setValue($ftp_creds['sftp_root']);
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Root is required'));
    }
    
    /**
     * @depends testAddSftpStorageRootGoodValue
     */
    public function testAddSftpStorageConnectionTimeoutNoValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_timeout')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Timeout is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Timeout must be a number'));
    }
    
    /**
     * @depends testAddSftpStorageConnectionTimeoutNoValue
     */
    public function testAddSftpStorageConnectionTimeoutStringValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_timeout')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Timeout is required'));
        $this->assertTrue($this->session->getPage()->hasContent('Sftp Timeout must be a number'));
    }
    
    /**
     * @depends testAddSftpStorageConnectionTimeoutStringValue
     */
    public function testAddSftpStorageConnectionTimeoutGoodValue()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('sftp_timeout')->setValue('30');
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Timeout is required'));
        $this->assertNotTrue($this->session->getPage()->hasContent('Sftp Timeout must be a number'));
    }
    
    /**
     * @depends testAddSftpStorageConnectionTimeoutGoodValue
     */
    public function testAddSftpStorageLocationStatusChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationStatusChecked
     */
    public function testAddSftpStorageLocationStatusUnChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_status')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_status')->isChecked());
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location Status is required unless you have more than 1 Storage Location'));
    }
    
    /**
     * @depends testAddSftpStorageLocationStatusUnChecked
     */
    public function testAddSftpStorageLocationFileUseChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationFileUseChecked
     */
    public function testAddSftpStorageLocationFileUseUnChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_file_use')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_file_use')->isChecked());
        $this->assertTrue($this->session->getPage()->hasContent('Storage Location File Use is required unless you have more than 1 Storage Location'));
    }
    
    /**
     * @depends testAddSftpStorageLocationFileUseUnChecked
     */
    public function testAddSftpStorageLocationDbUseChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_db_use')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_db_use')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationDbUseChecked
     */
    public function testAddSftpStorageLocationDbUseUnChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationDbUseUnChecked
     */
    public function testAddSftpStorageLocationIncludePruneChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->check();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationIncludePruneChecked
     */
    public function testAddSftpStorageLocationIncludePruneUnChecked()
    {
        $ftp_creds = $this->getSftpCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_sftp_storage') );
    
        $page = $this->session->getPage();
        $page->findById('storage_location_include_prune')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
    
        $this->assertNotTrue($this->session->getPage()->findById('storage_location_include_prune')->isChecked());
    }
    
    /**
     * @depends testAddSftpStorageLocationIncludePruneUnChecked
     */
    public function testAddSftpStorageLocationComplete()
    {
        $page = $this->setupSftpStorageLocation();
        $this->assertTrue($this->session->getPage()->hasContent('Created Date'));
        $this->assertNotTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
        $this->uninstall_addon();
    }
    
}