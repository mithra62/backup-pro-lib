<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Settings/Db.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Settings Db object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class DbBackup extends TestFixture
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

    public function testDbBackupMaxDbBackupEmptyValue()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('max_db_backups')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max Db Backups is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max Db Backups must be a number'));
    }

    /**
     * @depends testDbBackupMaxDbBackupEmptyValue
     */
    public function testDbBackupMaxDbBackupStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('max_db_backups')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max Db Backups is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Max Db Backups must be a number'));
    }

    /**
     * @depends testDbBackupMaxDbBackupStringValue
     */
    public function testDbBackupMaxDbBackupGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('max_db_backups')->setValue(4);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max Db Backups is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Max Db Backups must be a number'));
    }

    /**
     * @depends testDbBackupMaxDbBackupGoodValue
     */
    public function testDbBackupDbBackupAlertFreqEmptyValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_alert_threshold')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testDbBackupDbBackupAlertFreqEmptyValue
     */
    public function testDbBackupDbBackupAlertFreqStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_alert_threshold')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testDbBackupDbBackupAlertFreqStringValue
     */
    public function testDbBackupDbBackupAlertFreqGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_alert_threshold')->setValue(3);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Db Backup Alert Threshold must be a number'));
    }

    /**
     * @depends testDbBackupDbBackupAlertFreqGoodValue
     */
    public function testDbBackupDbMysqldumpNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('mysqldump');
        $page->findById('mysqldump_command')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Mysqldump Command is required'));
    }

    /**
     * @depends testDbBackupDbMysqldumpNoValue
     */
    public function testDbBackupDbMysqldumpGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('mysqldump');
        $page->findById('mysqldump_command')->setValue('mysqldump');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Mysqldump Command is required'));
    }

    /**
     * @depends testDbBackupDbMysqldumpNoValue
     */
    public function testDbBackupDbMysqlcliNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_restore_method')->selectOption('mysql');
        $page->findById('mysqlcli_command')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Mysqlcli Command is required'));
    }

    /**
     * @depends testDbBackupDbMysqlcliNoValue
     */
    public function testDbBackupDbMysqlcliGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_restore_method')->selectOption('mysql');
        $page->findById('mysqlcli_command')->setValue('mysql');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Mysqlcli Command is required'));
    }

    /**
     * @depends testDbBackupDbMysqlcliGoodValue
     */
    public function testSelectChunkLimitNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('php');
        
        sleep(1);
        $page->findById('php_backup_method_select_chunk_limit')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit must be a number'));
    }

    /**
     * @depends testSelectChunkLimitNoValue
     */
    public function testSelectChunkLimitStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('php');
        
        sleep(1);
        $page->findById('php_backup_method_select_chunk_limit')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit must be a number'));
    }

    /**
     * @depends testSelectChunkLimitStringValue
     */
    public function testSelectChunkLimitGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_db'));
        $page = $this->session->getPage();
        $page->findById('db_backup_method')->selectOption('php');
        
        sleep(1);
        $page->findById('php_backup_method_select_chunk_limit')->setValue(300);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('SELECT Chunk Limit must be a number'));
        $this->uninstall_addon();
    }
}