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
use Crell\ApiProblem\ApiProblem;
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
    public function __construct(\mithra62\Platforms\AbstractPlatform $platform, \mithra62\BackupPro\Rest $rest)
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
        
        if(!$this->authenticate())
        {
            http_response_code(403);
            exit;
        }
        
        $errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
            ->checkStorageLocations($this->settings['storage_details'])
            ->licenseCheck($this->settings['license_number'], $this->services['license']);
        
        if ($errors->totalErrors() == '0') {
            $errors = $errors->checkBackupState($this->services['backups'], $this->settings);
        }
        
        $this->errors = $errors->getErrors();
        $this->view_helper = new RestView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->m62->setService('view_helpers', function ($c) {
            return $this->view_helper;
        });
        
        //verify request auth
    }
    
    /**
     * Authenticates the requeset
     * @todo implement ;)
     * @return boolean
     */
    public function authenticate()
    {
        return true;
    }
    
    /**
     * Wrapper to handle error output
     *
     * Note that $detail should be a key for language translation
     *
     * @param int $code
     * @param string $detail
     * @param string $type
     * @param string $title
     * @param array $additional
     * @return \ZF\ApiProblem\ApiProblemResponse
     */
    public function setError($code, $detail, $type = null, $title = null, array $additional = array())
    {
        http_response_code($code);
        
        $problem = new ApiProblem($this->services['lang']->__($detail), $type, $title, $additional);
        header('Powered-By: Backup Pro '.self::version);
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos(strtolower($_SERVER['HTTP_ACCEPT_ENCODING']), 'xml') !== false)
        {
            header('Content-Type: application/problem+xml');
            return $problem->asXml(true);
        }
        else
        {
            header('Content-Type: application/problem+json');
            return $problem->asJson(true);
        }
    }
    
    /**
     * Handy little method to disable unused HTTP verb methods
     *
     * @return \ZF\ApiProblem\ApiProblemResponse
     */
    protected function methodNotAllowed()
    {
        return $this->setError(405, 'method_not_allowed');
    }    
    
    /**
     * Event to handle OPTION requests
     *
     * @param \Zend\Mvc\MvcEvent $e
     * @return void|\Zend\Stdlib\ResponseInterface
     */
    public function checkOptions(\Zend\Mvc\MvcEvent $e)
    {
        if ($this->params()->fromRoute('id', false)) {
            $options = $this->resourceOptions;
        } else {
            $options = $this->collectionOptions;
        }
    
        if (in_array($e->getRequest()->getMethod(), $options)) {
            return;
        }
    
        $response = $this->getResponse();
        $response->setStatusCode(405);
        return $response;
    }
    
    /**
     * Sets the HTTP header code that's passed
     *
     * @param int $code
     */
    public function setStatusCode($code)
    {
        $response = $this->getResponse();
        $response->setStatusCode($code);
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
    public function delete($id)
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
    public function put($id)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::replaceList()
     */
    public function replaceList($data)
    {
        return $this->methodNotAllowed();
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\Mvc\Controller\AbstractRestfulController::patchList()
     */
    public function patchList($data)
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