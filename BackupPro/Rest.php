<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest.php
 */
 
namespace mithra62\BackupPro;

use JaegerApp\Rest AS m62Rest;

/**
 * Backup Pro - Rest Object
 *
 * Handles restoring the system state
 *
 * @package Restore
 * @author Eric Lamb <eric@mithra62.com>
 */
class Rest extends m62Rest
{
    /**
     * (non-PHPdoc)
     * @see \mithra62\Rest::getServer()
     */
    public function getServer()
    {
        if(is_null($this->server))
        {
            $this->server = new Rest\Server($this->platform, $this);
        }
    
        return $this->server;
    }
}