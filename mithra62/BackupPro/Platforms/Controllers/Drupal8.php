<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Drupal8.php
 */
namespace mithra62\BackupPro\Platforms\Controllers;

use Drupal\Core\Controller\ControllerBase;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use mithra62\BackupPro\Platforms\Drupal8 as Platform;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\m62TwigExtension;

/**
 * Backup Pro - Drupal 8 Base Controller
 *
 * Starts the Controllers up
 *
 * @package BackupPro\Controllers
 * @author Eric Lamb <eric@mithra62.com>
 */
class Drupal8 extends ControllerBase implements ContainerAwareInterface
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
    protected $bp_errors = array();

    /**
     * Set it up
     * 
     * @param unknown $id            
     * @param string $module            
     */
    public function __construct()
    {
        $this->initController();
        $this->platform = new Platform();
        $this->m62->setService('platform', function ($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
            ->checkStorageLocations($this->settings['storage_details']);
            //->licenseCheck($this->settings['license_number'], $this->services['license']);
        
        if ($errors->totalErrors() == '0') {
            $errors = $errors->checkBackupState($this->services['backups'], $this->settings);
        }
        
        $this->bp_errors = $errors->getErrors();
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = NULL)
    {
        $this->twig = $container->get('twig');
        $this->view_helper = new m62TwigExtension($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        
        //register with Jaeger
        $this->m62->setService('view_helpers', function ($c) {
            return $this->view_helper;
        });
    }
}