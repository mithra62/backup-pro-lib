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
use mithra62\BackupPro\tests\TestTrait;

/**
 * mithra62 - (Selenium) Unit Test Fixture
 *
 * Contains all the tools the unit tests will rely on
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class TestFixture extends BrowserTestCase
{
    use TestTrait;
    
    /**
     * The browser config
     *
     * @var array
     */
    public static $browsers = array(
        '0' => array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.craft.clean.mithra62.com',
            'sessionStrategy' => 'shared'
        )
    );    
    
    public $rest_client = array();

    /**
     * Simple abstraction to determine the Platform specific URL we're attempting to hit
     * 
     * @param string $key            
     * @return string
     */
    protected function url($key)
    {
        return $this->urls[$key];
    }

    /**
     * Simple abstraction to determine the Platform specific Setting we want to apply
     * 
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
     * 
     * @return \Behat\Mink\Element\NodeElement
     */
    protected function setupFtpStorageLocation()
    {
        $ftp_creds = $this->getFtpCreds();
        
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_ftp_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My FTP Storage');
        $page->findById('ftp_hostname')->setValue($ftp_creds['ftp_hostname']);
        $page->findById('ftp_username')->setValue($ftp_creds['ftp_username']);
        $page->findById('ftp_password')->setValue($ftp_creds['ftp_password']);
        $page->findById('ftp_port')->setValue($ftp_creds['ftp_port']);
        $page->findById('ftp_store_location')->setValue($ftp_creds['ftp_store_location']);
        
        if ($ftp_creds['ftp_passive'] == 1) {
            $page->findById('ftp_passive')->check();
        }
        
        if ($ftp_creds['ftp_ssl'] == 1) {
            $page->findById('ftp_ssl')->check();
        }
        
        $page->findById('ftp_timeout')->setValue($ftp_creds['ftp_timeout']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up an Email Storage Location for use
     * 
     * @return \Behat\Mink\Element\NodeElement
     */
    public function setupEmailStorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_email_storage'));
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('Test Email Storage');
        $page->findById('email_storage_attach_threshold')->setValue('0');
        $page->findById('email_storage_emails')->setValue('eric@mithra62.com');
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up a Google Cloud Storage Location for use
     * 
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupGcsStorageLocation()
    {
        $gcs_creds = $this->getGcsCreds();
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_gcs_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My GCS Storage');
        $page->findById('gcs_access_key')->setValue($gcs_creds['gcs_access_key']);
        $page->findById('gcs_secret_key')->setValue($gcs_creds['gcs_secret_key']);
        $page->findById('gcs_bucket')->setValue($gcs_creds['gcs_bucket']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up a Local Storage Location for use
     * 
     * @param string $path
     *            The local path to the Storage Location
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupLocalStorageLocation($path)
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_local_storage'));
        
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Local Storage');
        $page->findById('backup_store_location')->setValue($path);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up a Rackspace Cloud Files Storage Location for use
     * 
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupRcfStorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_rcf_storage'));
        
        $rcf_creds = $this->getRcfCreds();
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Rackspace Storage');
        $page->findById('rcf_username')->setValue($rcf_creds['rcf_username']);
        $page->findById('rcf_api')->setValue($rcf_creds['rcf_api']);
        $page->findById('rcf_container')->setValue($rcf_creds['rcf_container']);
        $page->findById('rcf_location')->selectOption($rcf_creds['rfc_location']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up an Amazon S3 Storage Location for use
     * 
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupS3StorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_s3storage'));
        
        $rcf_creds = $this->getS3Creds();
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My S3 Storage');
        $page->findById('s3_access_key')->setValue($rcf_creds['s3_access_key']);
        $page->findById('s3_secret_key')->setValue($rcf_creds['s3_secret_key']);
        $page->findById('s3_bucket')->setValue($rcf_creds['s3_bucket']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up a Dropbox Storage Location for use
     * 
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupDropboxStorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_dropbox_storage'));
        
        $rcf_creds = $this->getDropboxCreds();
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My Dropbox Storage');
        $page->findById('dropbox_access_token')->setValue($rcf_creds['dropbox_access_token']);
        $page->findById('dropbox_app_secret')->setValue($rcf_creds['dropbox_app_secret']);
        $page->findById('dropbox_prefix')->setValue($rcf_creds['dropbox_prefix']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets up a SFTP Storage Location for use
     * 
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function setupSftpStorageLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('storage_add_sftp_storage'));
        
        $rcf_creds = $this->getSftpCreds();
        $page = $this->session->getPage();
        $page->findById('storage_location_name')->setValue('My SFTP Storage');
        $page->findById('sftp_host')->setValue($rcf_creds['sftp_host']);
        $page->findById('sftp_username')->setValue($rcf_creds['sftp_username']);
        $page->findById('sftp_password')->setValue($rcf_creds['sftp_password']);
        $page->findById('sftp_port')->setValue($rcf_creds['sftp_port']);
        $page->findById('sftp_root')->setValue($rcf_creds['sftp_root']);
        $page->findById('sftp_timeout')->setValue($rcf_creds['sftp_timeout']);
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     * Sets a workable "Working Directory" setting
     * 
     * @return unknown
     */
    public function setupGoodWorkingDirectory()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_general'));
        $page = $this->session->getPage();
        $page->findById('working_directory')->setValue($this->ts('working_directory'));
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    /**
     *
     * @return unknown
     */
    public function setupGoodLicenseKey()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_license'));
        $page = $this->session->getPage();
        $page->findById('license_number')->setValue('5214af45-9bc9-4019-8af9-bc98c38802c1');
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }

    public function setupGoodFileBackupLocation()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_files'));
        $page = $this->session->getPage();
        $page->findById('backup_file_location')->setValue(dirname(__FILE__));
        $page->findButton('m62_settings_submit')->submit();
        
        return $page;
    }
    
    public function takeDatabaseBackup()
    {
        $this->setupGoodWorkingDirectory();
        $this->session = $this->getSession();
        $this->session->visit($this->url('db_backup'));
        $page = $this->session->getPage();
        $page->findById('_backup_direct')->click();
        $this->iWaitForIdToAppear('backup_check_0');

        return $page;
    }
    
    public function takeFileBackup()
    {
        $this->setupGoodWorkingDirectory();
        $this->session = $this->getSession();
        $this->session->visit($this->url('file_backup'));
        $page = $this->session->getPage();
        $page->findById('_backup_direct')->click();
        $this->iWaitForIdToAppear('backup_check_0');
        return $page;
    }
    
    public function removeDatabaseBackup($confirm = true)
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('db_backups'));
        
        $page = $this->session->getPage();
        sleep(1); 
        $page->findById('backup_check_0')->check();
        $page->findButton('_remove_backup_button')->submit();
        
        $page = $this->session->getPage();
        $page->findButton('_remove_backup_button')->submit();
        
        return $page;
    }
    
    public function removeFileBackup($confirm = true)
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('file_backups'));
        
        $page = $this->session->getPage();
        sleep(1); 
        $page->findById('backup_check_0')->check();
        $page->findButton('_remove_backup_button')->submit();
        
        $page = $this->session->getPage();
        $page->findButton('_remove_backup_button')->submit();
        
        return $page;
    }
    
    public function setGoodRestApi()
    {
        $this->session = $this->getSession();
        $this->session->visit($this->url('settings_api'));
        
        $page = $this->session->getPage();
        sleep(1);
        $page->findById('enable_rest_api')->check();
        $page->findButton('m62_settings_submit')->submit();
        return $page;
    }
    
    public function setupRestApiClientCreds()
    {
        $this->rest_client_details = array();
        
        $this->session->visit($this->url('settings_api'));
        $page = $this->session->getPage();
        
        $url = $page->findById('rest_api_url_wrap');
        sleep(1);
        $api_url = $url->getAttribute('href');
        $api_key = $this->session->getPage()->findById('api_key')->getValue();
        $api_secret = $this->session->getPage()->findById('api_secret')->getValue();
        
        $this->rest_client_details = array(
            'site_url' => $api_url,
            'api_key' => $api_key,
            'api_secret' => $api_secret
        );
        
        return $this->rest_client_details;
    }
    
    /**
     * @When I wait for :text to appear
     * @Then I should see :text appear
     * @param $text
     * @throws \Exception
     */
    public function iWaitForIdToAppear($id)
    {
        $context = $this;
        $this->spin(function( $context ) use ($id) {
            try {
                $context->getSession()->getPage()->findById($id)->isVisible();
                return true;
            }
            catch(\Exception $e) {
                // NOOP
            }
            return false;
        });
    }
    
    
    /**
     * @When I wait for :text to disappear
     * @Then I should see :text disappear
     * @param $text
     * @throws \Exception
     */
    public function iWaitForIdToDisappear($id)
    {
        $context = $this;
        $this->spin(function( $context ) use ($id) {
            try {
                if(!$context->getSession()->getPage()->findById($id)) {
                    return true;
                }
            }
            catch(\Exception $e) {
                return true;
            }
            return false;
        });
    }
    
    public function spin ($lambda, $wait = 300)
    {
        for ($i = 0; $i < $wait; $i++)
        {
            try {
                if ($lambda($this)) {
                    return true;
                }
            } catch (\Exception $e) {
                // do nothing
            }
    
            sleep(1);
        }
    
        $backtrace = debug_backtrace();
    
        throw new \Exception(
            "Timeout thrown by " . $backtrace[1]['class'] . "::" . $backtrace[1]['function'] . "()\n" .
            $backtrace[1]['file'] . ", line " . $backtrace[1]['line']
        );
    }
    
}