<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/RestApi/Settings/FileBackup.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\RestApi\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;
use JaegerApp\Rest\Client AS BpApiClient;

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
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('max_file_backups' => '');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('max_file_backups', $data['errors']);
        $this->assertCount(2, $data['errors']['max_file_backups']);
        $this->assertTrue( in_array('Max File Backups is required', $data['errors']['max_file_backups']) );
        $this->assertTrue( in_array('Max File Backups must be a number', $data['errors']['max_file_backups']) );
    }

    /**
     * @depends testFileBackupMaxFileBackupsNoValue
     */
    public function testFileBackupMaxFileBackupsBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('max_file_backups' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('max_file_backups', $data['errors']);
        $this->assertCount(1, $data['errors']['max_file_backups']);
        $this->assertNotTrue( in_array('Max File Backups is required', $data['errors']['max_file_backups']) );
        $this->assertTrue( in_array('Max File Backups must be a number', $data['errors']['max_file_backups']) );
    }

    /**
     * @depends testFileBackupMaxFileBackupsBadValue
     */
    public function testFileBackupMaxFileBackupsGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('max_file_backups' => 4);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testFileBackupMaxFileBackupsGoodValue
     */
    public function testFileBackupBackupAlertThresholdNoValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('file_backup_alert_threshold' => '');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('file_backup_alert_threshold', $data['errors']);
        $this->assertCount(2, $data['errors']['file_backup_alert_threshold']);
        $this->assertTrue( in_array('File Backup Alert Threshold is required', $data['errors']['file_backup_alert_threshold']) );
        $this->assertTrue( in_array('File Backup Alert Threshold must be a number only', $data['errors']['file_backup_alert_threshold']) );
    }

    /**
     * @depends testFileBackupBackupAlertThresholdNoValue
     */
    public function testFileBackupBackupAlertThresholdBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('file_backup_alert_threshold' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('file_backup_alert_threshold', $data['errors']);
        $this->assertCount(1, $data['errors']['file_backup_alert_threshold']);
        $this->assertNotTrue( in_array('File Backup Alert Threshold is required', $data['errors']['file_backup_alert_threshold']) );
        $this->assertTrue( in_array('File Backup Alert Threshold must be a number only', $data['errors']['file_backup_alert_threshold']) );
    }

    /**
     * @depends testFileBackupBackupAlertThresholdBadValue
     */
    public function testFileBackupBackupAlertThresholdGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('file_backup_alert_threshold' => 4);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testFileBackupBackupAlertThresholdGoodValue
     */
    public function testFileBackupFileBackupLocationsNoValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('backup_file_location' => '');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('backup_file_location', $data['errors']);
        $this->assertCount(1, $data['errors']['backup_file_location']);
        $this->assertTrue( in_array('Backup File Location is required', $data['errors']['backup_file_location']) );
    }

    /**
     * @depends testFileBackupFileBackupLocationsNoValue
     */
    public function testFileBackupFileBackupLocationsBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('backup_file_location' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('backup_file_location', $data['errors']);
        $this->assertCount(1, $data['errors']['backup_file_location']);
        $this->assertNotTrue( in_array('Backup File Location is required', $data['errors']['backup_file_location']) );
        $this->assertTrue( in_array('"fdsafdsa" isn\'t a valid path on the system.', $data['errors']['backup_file_location']) );
    }

    /**
     * @depends testFileBackupFileBackupLocationsBadValue
     */
    public function testFileBackupFileBackupLocationsGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('backup_file_location' => dirname(__FILE__));
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testFileBackupFileBackupLocationsGoodValue
     */
    public function testFileBackupExcludePathsBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('exclude_paths' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('exclude_paths', $data['errors']);
        $this->assertCount(1, $data['errors']['exclude_paths']);
        $this->assertTrue( in_array('"fdsafdsa" isn\'t a valid regular expression or path on the system.', $data['errors']['exclude_paths']) );
    }

    /**
     * @depends testFileBackupExcludePathsBadValue
     */
    public function testFileBackupExcludePathsGoodPathValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('exclude_paths' => dirname(__FILE__));
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testFileBackupExcludePathsGoodPathValue
     */
    public function testFileBackupExcludeRegexCheck()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('regex_file_exclude' => 1);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testFileBackupExcludeRegexCheck
     */
    public function testFileBackupExcludeRegexUnCheck()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('regex_file_exclude' => 0);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
        
        $this->uninstall_addon();
    }
}