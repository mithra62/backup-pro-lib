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

use JaegerApp\Bootstrap as m62Boostrap;
use mithra62\BackupPro\Settings;
use mithra62\BackupPro\Backups;
use mithra62\BackupPro\Backup;
use mithra62\BackupPro\Restore;
use mithra62\BackupPro\Errors;
use mithra62\BackupPro\Notify;
use mithra62\BackupPro\Console;
use mithra62\BackupPro\Rest;
use mithra62\BackupPro\ErrorHandler;
use mithra62\BackupPro\Validate\Settings as valSettings;
use PHPSQL\Parser;

/**
 * Backup Pro - Bootstrap Object
 *
 * Sets up the environment and needed objects for Backup Pro
 *
 * @package Bootstrap
 * @author Eric Lamb <eric@mithra62.com>
 */
class Bootstrap extends m62Boostrap
{

    /**
     * The path to where language files are kept
     * 
     * @var string
     */
    protected $lang_dir = null;

    /**
     *
     * @ignore
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLangPath(realpath(dirname(__FILE__) . '/language'));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\Bootstrap::getServices()
     */
    public function getServices()
    {
        $this->container = parent::getServices();
        $this->container['settings'] = function ($c) {
            $settings = new Settings($c['db'], $c['lang']);
            $db_info = $this->getDbConfig();
            $settings->setOverrides($c['platform']->getConfigOverrides())
                ->setEncrypt($c['encrypt'])
                ->setValidate($c['settings_validate'])
                ->setTable($db_info['settings_table_name']);
            return $settings;
        };
        
        $this->container['backups'] = function ($c) {
            $backups = new Backups($c['files']);
            $backups->setServices($c);
            return $backups;
        };
        
        $this->container['backup'] = function ($c) {
            $backup = new Backup($c['db']);
            $backup->setServices($c);
            return $backup;
        };
        
        $this->container['restore'] = function ($c) {
            $restore = new Restore($c['db']);
            $restore->setServices($c);
            return $restore;
        };
        
        $this->container['errors'] = function ($c) {
            $errors = new Errors();
            $errors->setValidation($c['validate']);
            return $errors;
        };
        
        $this->container['notify'] = function ($c) {
            $notify = new Notify();
            $view_dir = realpath(dirname(__FILE__) . '/view');
            $notify->setMail($c['email'])
                ->setViewPath($view_dir)
                ->setSettings($c['settings']->get());
            return $notify;
        };
        
        $this->container['settings_validate'] = function ($c) {
            $validate = new valSettings();
            $validate->setRegex($this->container['regex']);
            $validate->setDb($c['db']);
            $validate->setSqlParser(new Parser);
            //$validate
            return $validate;
        };
        
        $this->container['console'] = function ($c) {
            $console = new Console();
            $console->setLang($c['lang']);
            return $console;
        };
        
        $this->container['rest'] = function ($c) {
            $rest = new Rest();
            $rest->setLang($c['lang']);
            return $rest;
        };
        
        $this->container['error_handler'] = function ($c) {
            $eh = new ErrorHandler();
            $settings = $c['settings']->get();
            
            $log_path = $settings['error_log_file_path'];
            if($log_path == '') {
                $log_path = $settings['working_directory'].'/error.log';
            }
            $eh->setPathToLogFile($log_path);
            $eh->setDebugMode( $settings['debug_mode'] === '1' ? true : false);
            return $eh;
        };
        
        
        return $this->container;
    }
}