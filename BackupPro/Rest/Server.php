<?php
namespace mithra62\BackupPro\Rest;

use Respect\Rest\Router;

class Server
{
    public function run()
    {
        //http_response_code(200);
        $r3 = new Router('/backup_pro/api');
         
        $r3->any('/backups/*', 'mithra62\BackupPro\Rest\Routes\Backups');
        $r3->any('/backup/*', 'mithra62\BackupPro\Rest\Routes\Backup');
        $r3->any('/settings/*', 'mithra62\BackupPro\Rest\Routes\Settings');
        $r3->any('/storage/*', 'mithra62\BackupPro\Rest\Routes\Storage');
        $r3->any('/info/*', 'mithra62\BackupPro\Rest\Routes\Info');
    }
}