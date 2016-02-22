<?php
namespace mithra62\BackupPro\Rest\Routes;
use Respect\Rest\Routable; 

class Settings implements Routable {
    public function get($id = false) { 
        echo __METHOD__;
    }
    
    public function post()
    {
        echo __METHOD__;
    }
    
    public function delete($id) {
        echo __METHOD__; 
    }
    
    public function put($id) {
       echo __METHOD__;
    }
    
    public function options()
    {
        echo __METHOD__;
    }
}
