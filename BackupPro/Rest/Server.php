<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Server.php
 */
 
namespace mithra62\BackupPro\Rest;

use Respect\Rest\Router;

/**
 * Backup Pro - REST Server
 *
 * Sets up and fires off the Backup Pro REST Server
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Server
{
    /**
     * The Platform object
     * @var \mithra62\Platforms\AbstractPlatform
     */
    protected $platform = null;
    
    /**
     * The REST object
     * @var \mithra62\BackupPro\Rest
     */
    protected $rest = null;
    
    /**
     * Set it up
     * @param \mithra62\Platforms\AbstractPlatform $platform
     * @param \mithra62\BackupPro\Rest $rest
     */
    public function __construct(\mithra62\Platforms\AbstractPlatform $platform, \mithra62\BackupPro\Rest $rest)
    {
        $this->platform = $platform;
        $this->rest = $rest;
    }
    
    /**
     * Outlines the Server routes
     * @return void
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