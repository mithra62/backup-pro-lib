<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Magento.php
 */
namespace mithra62\BackupPro\Platforms\Controllers;

use mithra62\BackupPro\Platforms\Magento as Platform;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\Magento AS MagentoView;

/**
 * Backup Pro - Magento Base Controller
 *
 * Starts the Controllers up
 *
 * @package BackupPro\Controllers
 * @author Eric Lamb <eric@mithra62.com>
 */
class MagentoAdmin extends \Mage_Adminhtml_Controller_Action
{
    use Controller;

    /**
     * The abstracted platform object
     * 
     * @var \mithra62\Platforms\Craft
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
    protected $bp_errors = array();

    /**
     * Set it up
     * 
     * @param unknown $id            
     * @param string $module            
     */
    public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        $this->initController();
        $this->platform = new Platform();
        $this->m62->setService('platform', function ($c) { 
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
            ->checkStorageLocations($this->settings['storage_details']);
            //->licenseCheck($this->settings['license_number'], $this->services['license']);
        
        if ($errors->totalErrors() == '0') {
            $errors = $errors->checkBackupState($this->services['backups'], $this->settings);
        }
        
        $this->bp_errors = $errors->getErrors();
        
        $this->view_helper = new MagentoView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->m62->setService('view_helpers', function ($c) {
            return $this->view_helper;
        });
        
    }
}