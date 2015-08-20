<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Controllers/Wordpress.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers;

use mithra62\BackupPro\Platforms\Wordpress AS Platform;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\Wordpress AS WordpressView;

/**
 * Backup Pro - Wordpress Base Controller
 *
 * Starts the Controllers up
 *
 * @package 	BackupPro\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Wordpress
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
    public function __construct()
    {
        $this->initController();
        $this->platform = new Platform();
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
        
        $this->url_base = '/wp-admin/admin.php?page=backup_pro/';
    }
    
    /**
     * Outputs a template to the browser
     * 
     * Since Wordpress doesn't have an MVC design we're sort of forcing it here.
     * The idea is we just lock all view scripts to anything within the backup_pro 
     * folder and the rest will take care of itself. 
     * 
     * Oh, and yeah, I'm using extract for pulling out the $variables. Sue me.
     * 
     * @param string $template The path to the template we want to render
     * @param array $variables The variables the template is expecting
     */
    protected function renderTemplate($template, array $variables = array()) 
    {
        $path = WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.'backup_pro'.DIRECTORY_SEPARATOR.$template.'.php';
        extract($variables);
        include $path;
    }
    
    /**
     * Sets the BackupPro object for use
     * @param \BackupPro $backup_lib
     * @return \mithra62\BackupPro\Platforms\Controllers\Wordpress
     */
    public function setBackupLib(\BackupPro $backup_lib)
    {
        $this->backup_lib = $backup_lib;
        return $this;
    }
    
    /**
     * Handy little helper method to figure out the passed variables 
     * 
     * We check the _POST then _GET in that order. 
     * @param string $index The GET or POST variable we want
     * @param string $default The value to use if the expected isn't set
     * @return unknown|string
     */
    public function getPost($index, $default = false)
    {
        if ( isset($_POST[$index]) )
        {
            return $_POST[$index];
        }
        elseif( isset( $_GET[$index]) )
        {
            return $_GET[$index];
        }
        
        return $default;
    }
    
    /**
     * Sets the BackupPro library for use
     * @param BackupPro $context
     */
    public function setContext(\BackupPro $context)
    {
        $this->context = $context;
        return $this;
    }
}