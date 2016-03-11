<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Server.php
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
     * (non-PHPdoc)
     * @see \mithra62\Rest\AbstractServer::run()
     */
    public function run()
    {
        $r3 = new Router('/backup_pro/api');
        $r3->any('/backups/*', 'mithra62\BackupPro\Rest\Routes\Backups', array($this->platform, $this->rest));
        $r3->any('/settings/*', 'mithra62\BackupPro\Rest\Routes\Settings', array($this->platform, $this->rest));
        $r3->any('/storage/*', 'mithra62\BackupPro\Rest\Routes\Storage', array($this->platform, $this->rest));
        $r3->any('/info/*', 'mithra62\BackupPro\Rest\Routes\Info', array($this->platform, $this->rest));
    }
}