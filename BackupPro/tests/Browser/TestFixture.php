<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/TestFixture.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

use aik099\PHPUnit\BrowserTestCase;
use mithra62\tests\TestTrait;

/**
 * mithra62 - (Selenium) Unit Test Fixture
 *
 * Contains all the tools the unit tests will rely on
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
class TestFixture extends BrowserTestCase
{
    use TestTrait; 
    
    /**
     * The browser config
     * @var array
     */
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
        ),
    );
    
    /**
     * Simple abstraction to determine the Platform specific URL we're attempting to hit
     * @param string $key
     * @return string
     */
    protected function url($key)
    {
        return $this->urls[$key];
    }
    
    /**
     * Simple abstraction to determine the Platform specific Setting we want to apply
     * @param string $key
     * @return string
     */
    protected function ts($key)
    {
        return $this->test_settings[$key];
    }
    
    /**
     * Helper method to remove any JS alerts contained in the webpage
     */
    public function iDisableTheAlerts()
    {
       $javascript = "window.confirm = function() {};";
       $this->session->executeScript($javascript);
    }
    
    /**
     * Sets up an FTP Storage Location for use
     * @return \Behat\Mink\Element\NodeElement
     */
    protected function setupFtpStorageLocation()
    {
        $ftp_creds = $this->getFtpCreds();
        
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_ftp_storage') );
        
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My FTP Storage');
        $page->findById('ftp_hostname')->setValue($ftp_creds['ftp_hostname']);
        $page->findById('ftp_username')->setValue($ftp_creds['ftp_username']);
        $page->findById('ftp_password')->setValue($ftp_creds['ftp_password']);
        $page->findById('ftp_port')->setValue($ftp_creds['ftp_port']);
        $page->findById('ftp_store_location')->setValue($ftp_creds['ftp_store_location']);
        
        if( $ftp_creds['ftp_passive'] == 1 )
        {
            $page->findById('ftp_passive')->check();
        }       
        
        if( $ftp_creds['ftp_ssl'] == 1 )
        {
            $page->findById('ftp_ssl')->check();
        }
        
        $page->findById('ftp_timeout')->setValue($ftp_creds['ftp_timeout']);
        $page->findButton('m62_settings_submit')->submit();  
        
        return $page;
    }
    
    /**
     * Sets up an Email Storage Location for use
     * @return \Behat\Mink\Element\NodeElement
     */
    public function setupEmailStorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_email_storage') );
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('Test Email Storage');
        $page->findById('email_storage_attach_threshold')->setValue('0');
        $page->findById('email_storage_emails')->setValue('eric@mithra62.com');
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }
    
    /**
     * Sets up a Google Cloud Storage Location for use
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupGcsStorageLocation()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit( $this->url('storage_add_gcs_storage') );
        
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My GCS Storage');
        $page->findById('gcs_access_key')->setValue($gcs_creds['gcs_access_key']);
        $page->findById('gcs_secret_key')->setValue($gcs_creds['gcs_secret_key']);
        $page->findById('gcs_bucket')->setValue($gcs_creds['gcs_bucket']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }
}