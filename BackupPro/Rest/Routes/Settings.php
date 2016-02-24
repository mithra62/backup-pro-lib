<?php
namespace mithra62\BackupPro\Rest\Routes;
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

class Settings extends RestController {
    
    /**
     * Maps the available HTTP verbs we support for groups of data
     *
     * @var array
     */
    protected $collection_options = array(
        'GET',
        'OPTIONS'
    );
    
    /**
     * Maps the available HTTP verbs for single items
     *
     * @var array
    */
    protected $resource_options = array(
        'GET',
        'PUT',
        'OPTIONS'
    );
    
    public function get($id = false) 
    { 
        
        if($id)
        {
            
        }
        else
        {
            
        }
        
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
}
