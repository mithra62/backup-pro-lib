<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Traits/Controller.php
 */
namespace mithra62\BackupPro\Traits;

use mithra62\BackupPro\Bootstrap;
use mithra62\Traits\Log;

/**
 * Backup Pro - Controller Trait
 *
 * Contains all the base startup details to use the mithra62 libraries for everything
 *
 * @package BackupPro\Controllers
 * @author Eric Lamb <eric@mithra62.com>
 */
trait Controller
{
    use Log;
    
    /**
     * The mithra62 bootstrap object
     * 
     * @var \mithra62\Bootstrap
     */
    protected $m62 = null;

    /**
     * The available services for use
     * 
     * @var array
     */
    protected $services = array();

    /**
     * Sets everything up for use
     *
     * Should be called before any thing else!
     */
    public function initController()
    {
        $this->m62 = new Bootstrap();
        $this->services = $this->m62->getServices();
    }

    /**
     * Takes an array and returns an empty key => value set
     * 
     * @param array $data            
     * @return array
     */
    public function returnEmpty(array $data)
    {
        $return = array();
        foreach ($data as $key => $value) {
            $return[$key] = '';
        }
        
        return $return;
    }
    
    /**
     * Returns the 
     * @param unknown $file_name
     */
    public function getDbBackupMetaName($file_name)
    {
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        return $storage->getStorage()->getDbBackupNamePath($file_name);
    }
}