<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Console/Craft.php
 */
namespace mithra62\BackupPro\Platforms\Console;

use mithra62\BackupPro\Platforms\Craft as Platform;
use mithra62\BackupPro\Traits\Controller;
use Craft\BaseCommand;

/**
 * Backup Pro - Craft Base Controller
 *
 * Starts the Controllers up
 *
 * @package BackupPro\Controllers
 * @author Eric Lamb <eric@mithra62.com>
 */
class Craft extends BaseCommand
{
    use Controller;

    /**
     * The abstracted platform object
     * 
     * @var \mithra62\Platforms\Craft
     */
    protected $platform = null;

    /**
     * The Backup Pro Settings
     * 
     * @var array
     */
    protected $settings = array();

    /**
     * A container of system messages and errors
     * 
     * @var array
     */
    protected $errors = array();

    /**
     * Set it up
     * 
     * @param unknown $id            
     * @param string $module            
     */
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->initController();
        $this->platform = new Platform();
        $this->m62->setService('platform', function ($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $this->errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
            ->checkStorageLocations($this->settings['storage_details'])
            ->getErrors();
    }
}