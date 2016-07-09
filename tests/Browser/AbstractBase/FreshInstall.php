<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/FreshInstall.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Fresh Installation Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class FreshInstall extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var unknown
     */
    public $session = null;

    public function testDashboardDefault()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit($this->url('dashboard'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No backups exist yet.'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
        //$this->assertTrue($this->session->getPage()
        //    ->hasContent('Please enter your license number.'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Would you like to take a database backup now?'));
    }

    /**
     * @depends testDashboardDefault
     */
    public function testDbBackupViewDefault()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('db_backups'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No database backups exist yet.'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Would you like to take a database backup now?'));
    }

    /**
     * @depends testDbBackupViewDefault
     */
    public function testFileBackupViewDefault()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('file_backups'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No file backups exist yet.'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Would you like to take a file backup now?'));
    }

    /**
     * @depends testFileBackupViewDefault
     */
    public function testBackupDbConfirmDefault()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('db_backup'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('You\'re going to need to fix the below configuration errors before you can start taking backups:'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Setup Storage Location'));
    }

    /**
     * @depends testBackupDbConfirmDefault
     */
    public function testBackupFilesConfirmDefault()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('file_backup'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('You\'re going to need to fix the below configuration errors before you can start taking backups:'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No Storage Locations have been setup yet!'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Setup Storage Location'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('No File Backup Locations have been configured.'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Set File Backup Locations'));
    }

    /**
     * @depends testBackupFilesConfirmDefault
     */
    public function testNoBackupExistMessaging()
    {
        $this->session = $this->getSession();
        $this->setupGoodWorkingDirectory();
        $this->setupGoodLicenseKey();
        $this->setupLocalStorageLocation($this->ts('local_backup_store_location'));
        $this->setupGoodFileBackupLocation();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('No database backups exist yet'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Would you like to take a database backup now?'));
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('No file backups exist yet'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Would you like to take a file backup now?'));
        $this->uninstall_addon();
    }
}