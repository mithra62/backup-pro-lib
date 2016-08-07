<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/General.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\RestApi;

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
abstract class FreshInstall extends TestFixture
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

    public function testBackupPreventionErrors()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $data = $client->get('/info/site');
        
        $this->assertInstanceOf('JaegerApp\Rest\Client\Hal', $data);
        $site_data = $data->getData();
        $this->assertArrayHasKey('backup_prevention_errors', $site_data);
        //$this->assertCount(3, $site_data['backup_prevention_errors']);
    }
    
    /**
     * @depends testBackupPreventionErrors
     */    
    public function testSystemErrors()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $data = $client->get('/info/site');
        
        $this->assertInstanceOf('JaegerApp\Rest\Client\Hal', $data);
        $site_data = $data->getData();
        $this->assertArrayHasKey('_system_errors', $site_data);
        //$this->assertCount(2, $site_data['_system_errors']);
        
        $this->uninstall_addon();
    }
}