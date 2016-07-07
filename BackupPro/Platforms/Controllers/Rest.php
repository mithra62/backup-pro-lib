<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Controllers/Rest.php
 */
namespace mithra62\BackupPro\Platforms\Controllers;

use JaegerApp\Platforms\Controllers\Rest AS m62Rest;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\Rest as RestView;
use Respect\Rest\Routable;

/**
 * Backup Pro - Rest Base Controller
 *
 * Starts the Controllers up
 *
 * @package BackupPro\Controllers
 * @author Eric Lamb <eric@mithra62.com>
 */
class Rest extends m62Rest implements Routable, \mithra62\BackupPro\BackupPro
{
    use Controller;

    /**
     * The abstracted platform object
     * 
     * @var \mithra62\Platforms\Eecms
     */
    protected $platform = null;

    /**
     * The Backup Pro Settings
     * 
     * @var array
     */
    protected $settings = array();

    /**
     * A container of system messages and errors
     * 
     * @var array
     */
    protected $errors = array();

    /**
     * The View Helper
     * 
     * @var \mithra62\Platforms\View\Rest
     */
    protected $view_helper = null;
   
    /**
     * The Hal Object for creating the output
     * @var Nocarrier\Hal
     */
    protected $hal = null;
    
    /**
     * Set it up
     * 
     * @param \mithra62\Platforms\AbstractPlatform $platform
     */
    public function __construct(\mithra62\Platforms\AbstractPlatform $platform, \mithra62\Rest $rest)
    {   
        $this->initController();
        $this->platform = $platform;
        $this->rest = $rest;
        $this->m62->setService('platform', function ($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        
        $errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
        ->checkStorageLocations($this->settings['storage_details']);
        
        if ($errors->totalErrors() == '0') {
            $errors = $errors->checkBackupState($this->services['backups'], $this->settings);
        }
        
        $this->errors = $errors->getErrors();
        
        $this->view_helper = new RestView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->view_helper->setSystemErrors($this->errors);
        $this->m62->setService('view_helpers', function ($c) {
            return $this->view_helper;
        });
        
        //is the API even on?!?
        if($this->settings['enable_rest_api'] !== '1')
        {
            http_response_code(404);
            exit;
        }

        //verify request auth
        if(!$this->authenticate())
        {
            $error = array('errors' => array('Invalid authorization'));
            echo $this->view_helper->renderError(403, 'Unauthorized', $error);            
            exit;
        }
        


    }
    
    /**
     * Validates the POST'd backup data and returns the clean array
     * @param array $delete_backups
     * @param string $type
     * @return multitype:array
     */
    protected function validateBackups($delete_backups, $type)
    {
        if(!$delete_backups || count($delete_backups) == 0)
        {
            $error = array('errors' => array('No backups sent...'));
            echo $this->view_helper->renderError(422, 'unprocessable_entity', $error);
            exit;
        }
        
        $backups = array();
        $locations = $this->settings['storage_details'];
        $drivers = $this->services['backup']->getStorage()->getAvailableStorageDrivers();
        foreach($delete_backups AS $file_name)
        {
            if( $file_name != '' )
            {
                $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$type;
                $file_data = $this->services['backup']->getDetails()->getDetails($file_name, $path);
                if(!$file_data)
                {
                    $error = array('errors' => array('No valid backups sent...'));
                    echo $this->view_helper->renderError(422, 'unprocessable_entity', $error);
                    exit;
                }
    
                $file_data = $this->services['backups']->getBackupStorageData($file_data, $locations, $drivers);
                $backups[] = $file_data;
            }
        }
        
        if(count($backups) == 0)
        {
            $error = array('errors' => array('No valid backups sent...'));
            echo $this->view_helper->renderError(422, 'unprocessable_entity', $error);
            exit;
        }
         
        return $backups;
    }
}