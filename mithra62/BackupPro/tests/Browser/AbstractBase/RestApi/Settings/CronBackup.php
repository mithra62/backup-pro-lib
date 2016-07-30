<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Settings/CronBackup.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\RestApi\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;
use JaegerApp\Rest\Client AS BpApiClient;

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
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_emails' => "fdsafdsa\neric@mithra62.com\nuuuuuuuu");
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('cron_notify_emails', $data['errors']);
        $this->assertCount(2, $data['errors']['cron_notify_emails']);
        $this->assertTrue( in_array('"fdsafdsa" isn\'t a valid email', $data['errors']['cron_notify_emails']) );
        $this->assertTrue( in_array('"uuuuuuuu" isn\'t a valid email', $data['errors']['cron_notify_emails']) );
        $this->assertNotTrue( in_array('"eric@mithra62.com" isn\'t a valid email', $data['errors']['cron_notify_emails']) );
    }

    /**
     * @depends testCronBackupNotifyEmailBadValue
     */
    public function testCronEmailFormatTextValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_email_mailtype' => 'text');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testCronEmailFormatTextValue
     */
    public function testCronEmailFormatHtmlValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_email_mailtype' => 'html');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testCronEmailFormatHtmlValue
     */
    public function testCronBackupNotifyEmailSubjectNoValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_email_subject' => "");
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('cron_notify_email_subject', $data['errors']);
        $this->assertCount(1, $data['errors']['cron_notify_email_subject']);
        $this->assertTrue( in_array('Cron Notify Email Subject is required', $data['errors']['cron_notify_email_subject']) );
    }

    /**
     * @depends testCronBackupNotifyEmailSubjectNoValue
     */
    public function testCronBackupNotifyEmailSubjectGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_email_subject' => 'fdsafdsafdsa');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testCronBackupNotifyEmailSubjectGoodValue
     */
    public function testCronBackupNotifyEmailMessageNoValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_notify_email_message' => "");
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('cron_notify_email_message', $data['errors']);
        $this->assertCount(1, $data['errors']['cron_notify_email_message']);
        $this->assertTrue( in_array('Cron Notify Email Message is required', $data['errors']['cron_notify_email_message']) );

        $this->uninstall_addon();
    }
}