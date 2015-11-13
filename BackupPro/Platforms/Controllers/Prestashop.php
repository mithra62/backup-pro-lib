<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Prestashop.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers;

use mithra62\BackupPro\Platforms\Prestashop AS Platform;
use mithra62\BackupPro\Traits\Controller;

/**
 * Backup Pro - Prestashop Base Controller
 *
 * Starts the Controllers up
 *
 * @package 	BackupPro\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Prestashop extends \ModuleAdminController
{
    use Controller;
    
    /**
     * The abstracted platform object
     * @var \mithra62\Platforms\Craft
     */
    protected $platform = null;
    
    /**
     * The Backup Pro Settings
     * @var array
     */
    protected $settings = array();
    
    /**
     * A container of system messages and errors
     * @var array
     */
    protected $bp_errors = array();
    
    /**
     * Set it up
     * @param unknown $id
     * @param string $module
     */
    public function __construct()
    {
        parent::__construct();
        $this->initController();
        $this->platform = new Platform();
        $this->m62->setService('platform', function($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $this->bp_errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->licenseCheck($this->settings['license_number'], $this->services['license'])
                                                 ->getErrors();
    }
}