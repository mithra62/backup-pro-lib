<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/General.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\RestApi\Settings;

use mithra62\BackupPro\tests\Browser\TestFixture;
use JaegerApp\Rest\Client AS BpApiClient;

/**
 * mithra62 - (Selenium) Settings object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class General extends TestFixture
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

    public function testGeneralViewWorkingDirectoryFieldEmpty()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('working_directory' => '');
        $data = $client->put('/settings', $settings);
        
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('working_directory', $data['errors']);
        $this->assertCount(4, $data['errors']['working_directory']);
        $this->assertTrue( in_array('Working Directory is required', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be writable', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be a directory', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be readable', $data['errors']['working_directory']) );
        //$this->assertTrue( in_array('Working Directory has to be an empty directory', $data['errors']['working_directory']) );
        
    }

    /**
     * @depends testGeneralViewWorkingDirectoryFieldEmpty
     */
    public function testGeneralViewWorkingdirecotryFieldBadPath()
    {

        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('working_directory' => 'fdsafdsafdsa');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('working_directory', $data['errors']);
        $this->assertCount(4, $data['errors']['working_directory']); 
        $this->assertTrue( in_array('Working Directory has to be writable', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be a directory', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be readable', $data['errors']['working_directory']) );
        $this->assertTrue( in_array('Working Directory has to be an empty directory', $data['errors']['working_directory']) ); 
    }

    /**
     * @depends testGeneralViewWorkingdirecotryFieldBadPath
     */
    public function testGeneralViewWorkingdirecotryGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('working_directory' => $this->ts('working_directory'));
        $data = $client->put('/settings', $settings);

        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testGeneralViewWorkingdirecotryGoodValue
     */
    public function testGeneralCronQueryKeyEmptyField()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_query_key' => '');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('cron_query_key', $data['errors']);
        $this->assertCount(2, $data['errors']['cron_query_key']);
        $this->assertTrue( in_array('Cron Query Key is required', $data['errors']['cron_query_key']) );
        $this->assertTrue( in_array('Cron Query Key must be alpha-numeric only', $data['errors']['cron_query_key']) );
    }

    /**
     * @depends testGeneralCronQueryKeyEmptyField
     */
    public function testGeneralCronQueryBadValueField()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_query_key' => 'my=bad&key=test');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('cron_query_key', $data['errors']);
        $this->assertCount(1, $data['errors']['cron_query_key']);
        $this->assertTrue( in_array('Cron Query Key must be alpha-numeric only', $data['errors']['cron_query_key']) );
    }

    /**
     * @depends testGeneralCronQueryBadValueField
     */
    public function testGeneralCronQueryGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('cron_query_key' => $this->ts('cron_query_key'));
        $data = $client->put('/settings', $settings);

        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testGeneralCronQueryGoodValue
     */
    public function testGeneralDashboardRecentTotalEmptyField()
    {

        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('dashboard_recent_total' => '');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('dashboard_recent_total', $data['errors']);
        $this->assertCount(2, $data['errors']['dashboard_recent_total']);
        $this->assertTrue( in_array('Dashboard Recent Total is required', $data['errors']['dashboard_recent_total']) );
        $this->assertTrue( in_array('Dashboard Recent Total must be a number greater than 1', $data['errors']['dashboard_recent_total']) );
    }

    /**
     * @depends testGeneralDashboardRecentTotalEmptyField
     */
    public function testGeneralDashboardRecentTotalBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('dashboard_recent_total' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('dashboard_recent_total', $data['errors']);
        $this->assertCount(1, $data['errors']['dashboard_recent_total']);
        $this->assertTrue( in_array('Dashboard Recent Total must be a number greater than 1', $data['errors']['dashboard_recent_total']) );
        
    }

    /**
     * @depends testGeneralDashboardRecentTotalBadValue
     */
    public function testGeneralDashboardRecentTotalGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('dashboard_recent_total' => 10);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testGeneralDashboardRecentTotalGoodValue
     */
    public function testGeneralAutoThresholdGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('auto_threshold' => 104857600);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());

    }

    /**
     * @depends testGeneralAutoThresholdGoodValue
     */
    public function testGeneralAutoThresholdCustomEmptyValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('auto_threshold' => 'custom');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('auto_threshold_custom', $data['errors']);
        $this->assertCount(3, $data['errors']['auto_threshold_custom']);
        $this->assertTrue( in_array('Auto Threshold Custom is required', $data['errors']['auto_threshold_custom']) );
        $this->assertTrue( in_array('Auto Threshold Custom must be a number', $data['errors']['auto_threshold_custom']) );
        $this->assertTrue( in_array('Auto Threshold Custom must be at least 100MB (100000000)', $data['errors']['auto_threshold_custom']) );

    }

    /**
     * @depends testGeneralAutoThresholdCustomEmptyValue
     */
    public function testGeneralAutoThresholdCustomStringBadValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('auto_threshold' => 'custom', 'auto_threshold_custom' => 'fdsafdsa');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('auto_threshold_custom', $data['errors']);
        $this->assertCount(2, $data['errors']['auto_threshold_custom']);
        $this->assertTrue( in_array('Auto Threshold Custom must be a number', $data['errors']['auto_threshold_custom']) );
        $this->assertTrue( in_array('Auto Threshold Custom must be at least 100MB (100000000)', $data['errors']['auto_threshold_custom']) );
    }

    /**
     * @depends testGeneralAutoThresholdCustomStringBadValue
     */
    public function testGeneralAutoThresholdCustomNumberBadValue()
    {   
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('auto_threshold' => 'custom', 'auto_threshold_custom' => 99);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('auto_threshold_custom', $data['errors']);
        $this->assertCount(1, $data['errors']['auto_threshold_custom']);
        $this->assertTrue( in_array('Auto Threshold Custom must be at least 100MB (100000000)', $data['errors']['auto_threshold_custom']) );
    }

    /**
     * @depends testGeneralAutoThresholdCustomStringBadValue
     */
    public function testGeneralAutoThresholdCustomGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('auto_threshold' => 'custom', 'auto_threshold_custom' => 100000000);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testGeneralAutoThresholdCustomGoodValue
     */
    public function testGeneralAllowDuplicatesCheck()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('allow_duplicates' => 1);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
    }

    /**
     * @depends testGeneralAllowDuplicatesCheck
     */
    public function testGeneralAllowDuplicatesUnCheck()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('allow_duplicates' => 0);
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());
        
    }

    /**
     * @depends testGeneralAllowDuplicatesUnCheck
     */
    public function testGeneralDateFormatEmptyValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('date_format' => '');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(422, $data->getStatus());
        $this->assertArrayHasKey('date_format', $data['errors']);
        $this->assertCount(1, $data['errors']['date_format']);
        $this->assertTrue( in_array('Date Format is required', $data['errors']['date_format']) );

    }

    /**
     * @depends testGeneralDateFormatEmptyValue
     */
    public function testGeneralDateFormatGoodValue()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $settings = array('date_format' => 'M d, Y, h:i:sA');
        $data = $client->put('/settings', $settings);
        
        $this->assertInstanceOf('\JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/settings', $data->getUri());

        $this->uninstall_addon();
    }
}