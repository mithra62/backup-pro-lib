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
abstract class Hmac extends TestFixture
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

    public function test404OnNotEnabled()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit($this->url('settings_api'));
        $page = $this->session->getPage();
        
        $url = $page->findById('rest_api_url_wrap');
        $api_url = $url->getAttribute('href');
        
        $this->rest_client_details = array(
            'site_url' => $api_url,
            'api_key' => 'fdsa',
            'api_secret' => 'fdsa'
        );
        $client = new BpApiClient($this->rest_client_details);
        
        $data = $client->get('/info/site');
        $this->assertInstanceOf('\JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(404, $data->getStatus());
        $this->assertEquals('Not Found', $data->getTitle());
    }
    
    /**
     * @depends test404OnNotEnabled
     */    
    public function test403NotAuthorized()
    {
        $this->setGoodRestApi();
        $this->session->visit($this->url('settings_api'));
        $page = $this->session->getPage();
        
        $url = $page->findById('rest_api_url_wrap');
        $api_url = $url->getAttribute('href');
        
        $this->rest_client_details = array(
            'site_url' => $api_url,
            'api_key' => 'fdsa',
            'api_secret' => 'fdsa'
        );
        
        $client = new BpApiClient($this->rest_client_details);
        
        $data = $client->get('/info/site');
        $this->assertInstanceOf('JaegerApp\Rest\Client\ApiProblem', $data);
        $this->assertEquals(403, $data->getStatus());
        $this->assertEquals('Unauthorized', $data->getTitle());
    }
    
    /**
     * @depends test403NotAuthorized
     */    
    public function testAuthorized()
    {
        $this->setGoodRestApi();
        $this->setupRestApiClientCreds();
        
        $client = new BpApiClient($this->rest_client_details);
        $data = $client->get('/info/site');
        $this->assertInstanceOf('JaegerApp\Rest\Client\Hal', $data);
        $this->assertEquals('/info/site', $data->getUri());
        $this->uninstall_addon();
    }
    
}