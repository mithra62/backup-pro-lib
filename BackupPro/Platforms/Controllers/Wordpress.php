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
    
    protected $backup_lib = null;
    
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
        $this->errors = $this->services['errors']->setValidation($this->services['settings_validate'])->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->licenseCheck($this->settings['license_number'], $this->services['license'])
                                                 ->getErrors();
        
        $this->view_helper = new WordpressView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->m62->setService('view_helpers', function($c) {
            return $this->view_helper;
        });
        
        $this->url_base = '/wp-admin/admin.php?page=backup_pro/';
    }
    
    protected function renderTemplate($template, $variables) 
    {
        $path = WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.'backup_pro'.DIRECTORY_SEPARATOR.$template.'.php';
        extract($variables);
        include $path;
    }
    
    protected function setBackupLib($backup_lib)
    {
        $this->backup_lib = $backup_lib;
        return $this;
    }
    
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
}