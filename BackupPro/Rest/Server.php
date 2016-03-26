<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/V1/Server.php
 */
 
namespace mithra62\BackupPro\Rest;

use mithra62\Rest\AbstractServer;
use Respect\Rest\Router;

/**
 * Backup Pro - Rest Server
 *
 * Sets up and dispatches REST requests 
 *
 * @package Restore
 * @author Eric Lamb <eric@mithra62.com>
 */
class Server extends AbstractServer
{
    /**
     * The available versions our API supports
     * @var array
     */
    protected $api_versions = array('1');
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\Rest\AbstractServer::run()
     */
    public function run()
    {
        //determine the version
        $headers = \getallheaders();
        if(isset($headers['m62_bp_version']) && is_numeric($headers['m62_bp_version']) && in_array($headers['m62_bp_version'], $this->api_versions)) 
        {
            $version = 'V'.str_replace('.','_',$headers['m62_bp_version']);
        }
        else
        {
            $version = 'V1';
        }
        
        
        //now define the routes
        $r3 = new Router('/backup_pro/api');
        $r3->any('/backups/*', 'mithra62\BackupPro\Rest\Routes\\'.$version.'\Backups', array($this->platform, $this->rest));
        $r3->any('/settings/*', 'mithra62\BackupPro\Rest\Routes\\'.$version.'\Settings', array($this->platform, $this->rest));
        $r3->any('/storage/*', 'mithra62\BackupPro\Rest\Routes\\'.$version.'\Storage', array($this->platform, $this->rest));
        $r3->any('/info/*', 'mithra62\BackupPro\Rest\Routes\\'.$version.'\Info', array($this->platform, $this->rest));
    }
}