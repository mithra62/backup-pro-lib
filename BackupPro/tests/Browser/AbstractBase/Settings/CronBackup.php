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
abstract class CronBackup extends TestFixture
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

    public function testCronBackupNotifyEmailBadValue()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_emails')->setValue("fdsafdsa\neric@mithra62.com\nuuuuuuuu");
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('"fdsafdsa" isn\'t a valid email'));
        $this->assertTrue($this->session->getPage()
            ->hasContent('"uuuuuuuu" isn\'t a valid email'));
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('"eric@mithra62.com" isn\'t a valid email'));
    }

    /**
     * @depends testCronBackupNotifyEmailBadValue
     */
    public function testCronEmailFormatTextValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_email_mailtype')->setValue('text');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertEquals('text', $this->session->getPage()->findById('cron_notify_email_mailtype')->getValue());
    }

    /**
     * @depends testCronEmailFormatTextValue
     */
    public function testCronEmailFormatHtmlValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_email_mailtype')->setValue('html');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertEquals('html', $this->session->getPage()->findById('cron_notify_email_mailtype')->getValue());
    }

    /**
     * @depends testCronEmailFormatHtmlValue
     */
    public function testCronBackupNotifyEmailSubjectNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_email_subject')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Cron Notify Email Subject is required'));
    }

    /**
     * @depends testCronBackupNotifyEmailSubjectNoValue
     */
    public function testCronBackupNotifyEmailSubjectGoodValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_email_subject')->setValue('fdsafdsa');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertNotTrue($this->session->getPage()
            ->hasContent('Cron Notify Email Subject is required'));
    }

    /**
     * @depends testCronBackupNotifyEmailSubjectGoodValue
     */
    public function testCronBackupNotifyEmailMessageNoValue()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_cron'));
        $page = $this->session->getPage();
        $page->findById('cron_notify_email_message')->setValue('');
        $page->findButton('m62_settings_submit')->submit();
        
        $this->assertTrue($this->session->getPage()
            ->hasContent('Cron Notify Email Message is required'));
        $this->uninstall_addon();
    }
}