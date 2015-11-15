<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Controllers/Console.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers;

use mithra62\BackupPro\Platforms\Console AS Platform;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\Wordpress AS WordpressView;

/**
 * Backup Pro - Console Base Controller
 *
 * Starts the Controllers up
 *
 * @package 	BackupPro\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Console
{
    use Controller;
    
    /**
     * The abstracted platform object
     * @var \mithra62\Platforms\Eecms
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
    protected $errors = array();
    
    /**
     * The View Helper 
     * @var \mithra62\BackupPro\Platforms\View\Eecms
     */
    protected $view_helper = null;
    
    /**
     * The backup helper object
     * @var BackupPro
     */
    protected $backup_lib = null;
	
	/**
	 * An instance of the BackupPro object
	 * @var BackupPro
	 */
	protected $context = null;
    
    /**
     * Set it up
     * @param unknown $id
     * @param string $module
     */
    public function __construct( $config = array() )
    {
        $this->initController();
        $this->platform = new Platform();
        $this->platform->setConfig($config);
        $this->m62->setService('platform', function($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $this->errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->licenseCheck($this->settings['license_number'], $this->services['license'])
                                                 ->getErrors();
        
        $this->view_helper = new WordpressView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->m62->setService('view_helpers', function($c) {
            return $this->view_helper;
        });
    }
    
    /**
     * Boostrap for the Console interface
     */
    public function run()
    {
        
    }
}