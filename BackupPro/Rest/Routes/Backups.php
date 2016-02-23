<?php
namespace mithra62\BackupPro\Rest\Routes;

use Respect\Rest\Routable; 
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

class Backups extends RestController implements Routable {
    public function get($id = false) { 
        
        //$accept = (array) explode(';', $_SERVER['HTTP_ACCEPT']);
        //print_r($accept);
        echo __METHOD__;
    }
    
    public function post()
    {
        echo __METHOD__;
    }
    
    public function delete($id) 
    { 
        echo __METHOD__;
    }
    
    public function put($id) { 
        echo __METHOD__;
    }
}
