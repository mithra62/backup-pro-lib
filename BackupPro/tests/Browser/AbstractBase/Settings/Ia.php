<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Settings/CronBackup.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Cron Backup File object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class Ia extends TestFixture
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

    public function testIaVerificationTempDatabaseBadValue()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('db_verification_db_name')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t available to your configured database connection'));
    }

    /**
     * @depends testIaVerificationTempDatabaseBadValue
     */
    public function testIaVerificationTempDatabaseGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('db_verification_db_name')->setValue('test_backup_pro_verification');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('"test_backup_pro_verification" isn\'t available to your configured database connection'));
    }

    /**
     * @depends testIaVerificationTempDatabaseGoodValue
     */
    public function testIaTotalExecutionsPerExecutionNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('total_verifications_per_execution')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution must be a whole number'));
    }

    /**
     * @depends testIaTotalExecutionsPerExecutionNoValue
     */
    public function testIaTotalExecutionsPerExecutionStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('total_verifications_per_execution')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution must be a whole number'));
    }

    /**
     * @depends testIaTotalExecutionsPerExecutionStringValue
     */
    public function testIaTotalExecutionsPerExecutionGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('total_verifications_per_execution')->setValue(4);
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution is required'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Total Verifications Per Execution must be a whole number'));
    }

    /**
     * @depends testIaTotalExecutionsPerExecutionStringValue
     */
    public function testIaBackupMissingScheduleEmailIntervalEmptyValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_interval')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Interval is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Interval must be a whole number'));
    }

    /**
     * @depends testIaBackupMissingScheduleEmailIntervalEmptyValue
     */
    public function testIaBackupMissingScheduleEmailIntervalStringValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_interval')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Interval is required'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Interval must be a whole number'));
    }

    /**
     * @depends testIaBackupMissingScheduleEmailIntervalStringValue
     */
    public function testIaBackupMissedScheduleNotifyEmailsBadValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_emails')->setValue("fdsafdsa\neric@mithra62.com\nuuuuuuuu");
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid email'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('"uuuuuuuu" isn\'t a valid email'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('"eric@mithra62.com" isn\'t a valid email'));
    }

    /**
     * @depends testIaBackupMissedScheduleNotifyEmailsBadValue
     */
    public function testIaBackupMissedScheduleEmailFormatTextValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_mailtype')->setValue('text');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertEquals('text', $this->session->getPage()->findById('backup_missed_schedule_notify_email_mailtype')->getValue());
    }

    /**
     * @depends testIaBackupMissedScheduleEmailFormatTextValue
     */
    public function testIaBackupMissedScheduleEmailFormatHtmlValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_mailtype')->setValue('html');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertEquals('html', $this->session->getPage()->findById('backup_missed_schedule_notify_email_mailtype')->getValue());
    }

    /**
     * @depends testIaBackupMissedScheduleEmailFormatHtmlValue
     */
    public function testIaBackupMissedScheduleEmailSubjectNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_subject')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Subject is required'));
    }

    /**
     * @depends testIaBackupMissedScheduleEmailSubjectNoValue
     */
    public function testIaBackupMissedScheduleEmailSubjectGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_subject')->setValue('My Test Subject');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Subject is required'));
    }

    /**
     * @depends testIaBackupMissedScheduleEmailSubjectGoodValue
     */
    public function testIaBackupMissedScheduleEmailMessageNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_message')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Message is required'));
    }

    /**
     * @depends testIaBackupMissedScheduleEmailMessageNoValue
     */
    public function testIaBackupMissedScheduleEmailMessageGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_ia'));
        $page = $this->session->getPage();
        $page->findById('backup_missed_schedule_notify_email_message')->setValue('My Test Message');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Backup Missed Schedule Notify Email Message is required'));
        $this->uninstall_addon();
    }
}