<?php
namespace mithra62\BackupPro\Rest\Routes;
use Respect\Rest\Routable; 
use mithra62\BackupPro\Platforms\Controllers\Rest;

class Backups extends Rest implements Routable {
    public function get($id = false) { 
        echo $id;
    }
    
    public function post()
    {
        echo 'fff';
    }
    
    public function delete($id) { }
    public function put($id) { 
        echo $id.'eric';
    }
}
