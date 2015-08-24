<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Bootstrap.php
 */
 
namespace mithra62\BackupPro;

use mithra62\Bootstrap AS m62Boostrap; 
use mithra62\BackupPro\Settings;
use mithra62\BackupPro\Backups;
use mithra62\BackupPro\Backup;
use mithra62\BackupPro\Restore;
use mithra62\BackupPro\Errors;
use mithra62\BackupPro\Notify; 
use mithra62\BackupPro\Validate\Settings AS valSettings;

/**
 * Backup Pro - Bootstrap Object
 *
 * Sets up the environment and needed objects for Backup Pro
 *
 * @package 	Bootstrap
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Bootstrap extends m62Boostrap
{
    /**
     * The path to where language files are kept
     * @var string
     */
    protected $lang_dir = null;
    
    /**
     * @ignore
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLangPath(realpath(dirname(__FILE__).'/language'));
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\Bootstrap::getServices()
     */
    public function getServices()
    {
        $this->container = parent::getServices();
        $this->container['settings'] = function($c) {
            $settings = new Settings($c['db'], $c['lang']);
            $db_info = $this->getDbConfig();
            $settings->setOverrides($c['platform']->getConfigOverrides())->setEncrypt($c['encrypt'])->setValidate($c['settings_validate'])->setTable($db_info['settings_table_name']);
            return $settings;
        };

        $this->container['backups'] = function($c) {
            $backups = new Backups($c['files']);
            return $backups;
        };

        $this->container['backup'] = function($c) {
            $backup = new Backup($c['db']);
            $backup->setServices($c);
            return $backup;
        };

        $this->container['restore'] = function($c) {
            $restore = new Restore($c['db']);
            $restore->setServices($c);
            return $restore;
        };
        
        $this->container['errors'] = function($c) {
            $errors = new Errors();
            $errors->setValidation($c['validate']);
            return $errors;
        };

        $this->container['notify'] = function($c) {
            $notify = new Notify();
            $view_dir = realpath(dirname(__FILE__).'/view');
            $notify->setMail($c['email'])->setViewPath($view_dir)->setSettings($c['settings']->get());
            return $notify;
        };
        
        $this->container['settings_validate'] = function($c) {
            $validate = new valSettings();
            $validate->setRegex($this->container['regex']);
            return $validate;
        };
        
        return $this->container;
    }
}