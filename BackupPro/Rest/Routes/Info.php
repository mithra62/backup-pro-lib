<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/Info.php
 */
 
namespace mithra62\BackupPro\Rest\Routes;
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

/**
 * Backup Pro - Site Info REST Route
 *
 * Contains the REST specific verbs for the API
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Info extends RestController {
    
    public function get($id = false)
    {
        switch($id)
        {
            case 'constants':
               return $this->phpConstants(); 
            break;
            
            case 'site':
                return $this->siteDetails();
            break;
            
            default: 
                return $this->iniAll();
            break;
        }
    }
    
    private function siteDetails()
    {
        $parts = explode('\\', get_class($this->platform));
        $data = array(
            'site_url' => $this->platform->getSiteUrl(),
            'site_name' => $this->platform->getSiteName(),
            'platform' => end($parts)
        );
        $hal = $this->view_helper->prepareSystemInfoCollection('/info/site', $data);
        return $this->view_helper->renderOutput($hal);
    }
    
    private function iniAll()
    {
        $data = ini_get_all();
        $hal = $this->view_helper->prepareSystemInfoCollection('/info', $data);
        return $this->view_helper->renderOutput($hal);
    }
    
    private function phpConstants()
    {
        $data = get_defined_constants();
        print_r($data);
        //exit;
        $hal = $this->view_helper->prepareSystemInfoCollection('/info', $data);
        return $this->view_helper->renderOutput($hal);
    }
}