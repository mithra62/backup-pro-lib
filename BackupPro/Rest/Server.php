<?php
namespace mithra62\BackupPro\Rest;

use Respect\Rest\Router;

class Server
{
    protected $platform = null;
    protected $rest = null;
    
    public function __construct(\mithra62\Platforms\AbstractPlatform $platform, \mithra62\BackupPro\Rest $rest)
    {
        $this->platform = $platform;
        $this->rest = $rest;
    }
    
    public function run()
    {
        //http_response_code(200);
        $r3 = new Router('/backup_pro/api');
         
        $r3->any('/backups/*', 'mithra62\BackupPro\Rest\Routes\Backups', array($this->platform, $this->rest));
        $r3->any('/backup/*', 'mithra62\BackupPro\Rest\Routes\Backup', array($this->platform, $this->rest));
        $r3->any('/settings/*', 'mithra62\BackupPro\Rest\Routes\Settings', array($this->platform, $this->rest));
        $r3->any('/storage/*', 'mithra62\BackupPro\Rest\Routes\Storage', array($this->platform, $this->rest));
        $r3->any('/info/*', 'mithra62\BackupPro\Rest\Routes\Info', array($this->platform, $this->rest));
    }
}