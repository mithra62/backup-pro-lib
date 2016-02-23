<?php
namespace mithra62\BackupPro\Rest\Routes;

use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

class Backups extends RestController {

    /**
     * Maps the available HTTP verbs we support for groups of data
     *
     * @var array
     */
    protected $collectionOptions = array(
        'GET',
        'POST',
        'OPTIONS'
    );
    
    /**
     * Maps the available HTTP verbs for single items
     *
     * @var array
    */
    protected $resourceOptions = array(
        'GET',
        'POST',
        'DELETE',
        'PUT',
        'OPTIONS'
    );
    
    public function get($id = false) { 
        
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
