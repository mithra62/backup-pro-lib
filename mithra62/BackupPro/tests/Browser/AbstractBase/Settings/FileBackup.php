<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Settings/File.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Settings File object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class FileBackup extends TestFixture
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

    public function testFileBackupMaxFileBackupsNoValue()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('max_file_backups')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max File Backups is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max File Backups must be a number'));
    }

    /**
     * @depends testFileBackupMaxFileBackupsNoValue
     */
    public function testFileBackupMaxFileBackupsBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('max_file_backups')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max File Backups is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max File Backups must be a number'));
    }

    /**
     * @depends testFileBackupMaxFileBackupsBadValue
     */
    public function testFileBackupMaxFileBackupsGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('max_file_backups')->setValue(4);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max File Backups is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max File Backups must be a number'));
    }

    /**
     * @depends testFileBackupMaxFileBackupsGoodValue
     */
    public function testFileBackupBackupAlertThresholdNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('file_backup_alert_threshold')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testFileBackupBackupAlertThresholdNoValue
     */
    public function testFileBackupBackupAlertThresholdBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('file_backup_alert_threshold')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testFileBackupBackupAlertThresholdBadValue
     */
    public function testFileBackupBackupAlertThresholdGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('file_backup_alert_threshold')->setValue(4);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('File Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testFileBackupBackupAlertThresholdGoodValue
     */
    public function testFileBackupFileBackupLocationsNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('backup_file_location')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup File Location is required'));
    }

    /**
     * @depends testFileBackupFileBackupLocationsNoValue
     */
    public function testFileBackupFileBackupLocationsBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('backup_file_location')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup File Location is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid path on the system.'));
    }

    /**
     * @depends testFileBackupFileBackupLocationsBadValue
     */
    public function testFileBackupFileBackupLocationsGoodValue()
    {
        $this->setupGoodFileBackupLocation();
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup File Location is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid path on the system.'));
    }

    /**
     * @depends testFileBackupFileBackupLocationsGoodValue
     */
    public function testFileBackupExcludePathsBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('exclude_paths')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid regular expression or path on the system.'));
    }

    /**
     * @depends testFileBackupExcludePathsBadValue
     */
    public function testFileBackupExcludePathsGoodPathValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('exclude_paths')->setValue(dirname(__FILE__));
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid regular expression or path on the system.'));
    }

    /**
     * @depends testFileBackupExcludePathsGoodPathValue
     */
    public function testFileBackupExcludeRegexCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        
        $page = $this->session->getPage();
        $page->findById('regex_file_exclude')->check();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->findById('regex_file_exclude')
            ->isChecked());
    }

    /**
     * @depends testFileBackupExcludeRegexCheck
     */
    public function testFileBackupExcludeRegexUnCheck()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        
        $page = $this->session->getPage();
        $page->findById('regex_file_exclude')->uncheck();
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->findById('regex_file_exclude')
            ->isChecked());
        $this->uninstall_addon();
    }
}