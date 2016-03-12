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
class Rest implements Routable, \mithra62\BackupPro\BackupPro
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
        
        //is the API even on?!?
        if($this->settings['enable_rest_api'] !== '1')
        {
            http_response_code(404);
            exit;
        }

        //verify request auth
        if(!$this->authenticate())
        {
            http_response_code(403);
            exit;
        }
        
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
    }
    
    /**
     * Authenticates the request
     * @return boolean
     */
    public function authenticate()
    {
        $data = $this->getRequestHeaders();
        $hmac = $this->rest->getServer()->getHmac();
        return $hmac->setData($data)
                    ->setRoute($this->platform->getPost('bp_method'))
                    ->setMethod($_SERVER['REQUEST_METHOD'])
                    ->auth($this->settings['api_key'], $this->settings['api_secret']);
    }
    
    /**
     * Returns the input data as an array
     * @return array
     */
    public function getBodyData()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if(!$data)
        {
            return array();
        }
        
        return $data;
    }
    
    /**
     * Returns an associative array of the request headers
     * @return multitype:unknown
     */
    public function getRequestHeaders() 
    {
        return \getallheaders();
    }
    
    /**
     * Handy little method to disable unused HTTP verb methods
     *
     * @return \ZF\ApiProblem\ApiProblemResponse
     */
    protected function methodNotAllowed()
    {
        return $this->view_helper->renderError(405, 'method_not_allowed');
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
        
        $encrypt = $this->services['encrypt'];
        $backups = array();
         
        $locations = $this->settings['storage_details'];
        $drivers = $this->services['backup']->getStorage()->getAvailableStorageDrivers();
        foreach($delete_backups AS $file_name)
        {
            $file_name = $encrypt->decode($file_name);
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
    
    /**
     * Prepares the OPTIONS verb
     * @param string $id
     */
    public function options($id = false)
    {
        return;
        if ($id) {
            echo 'fdsa';
            $options = $this->resourceOptions;
        } else {
            $options = $this->collectionOptions;
        }
   
        echo $id;
        exit;
        print_R($this->resourceOptions);
        header('Allow: '. implode(',', $options));
        exit;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
     */
    public function create($data)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
     */
    public function post()
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::delete()
     */
    public function delete($id = false)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::deleteList()
     */
    public function deleteList()
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::get()
     */
    public function get($id = false)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::getList()
     */
    public function getList()
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::head()
     */
    public function head($id = null)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::patch()
     */
    public function patch($id)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::patch()
     */
    public function put($id = false)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::update()
     */
    public function update($id, $data)
    {
        return $this->methodNotAllowed();
    }    
}