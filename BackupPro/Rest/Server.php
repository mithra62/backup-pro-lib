<?php
namespace mithra62\BackupPro\Rest;

use mithra62\Rest\AbstractServer;
use Respect\Rest\Router;

class Server extends AbstractServer
{
    public function run()
    {
        $r3 = new Router('/backup_pro/api');
        $r3->any('/backups/*', 'mithra62\BackupPro\Rest\Routes\Backups', array($this->platform, $this->rest));
        $r3->any('/settings/*', 'mithra62\BackupPro\Rest\Routes\Settings', array($this->platform, $this->rest));
        $r3->any('/storage/*', 'mithra62\BackupPro\Rest\Routes\Storage', array($this->platform, $this->rest));
        $r3->any('/info/*', 'mithra62\BackupPro\Rest\Routes\Info', array($this->platform, $this->rest));
    }
}