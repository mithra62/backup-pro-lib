<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Remote.php
 */
 
namespace mithra62\BackupPro;

use mithra62\Remote AS m62Remote;

/**
 * Backup Pro - Remote Object
 *
 * Abstracts file system work 
 *
 * @package 	BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Remote extends m62Remote
{
    /**
     * (non-PHPdoc)
     * @see \League\Flysystem\Filesystem::copy()
     */
    public function copy($path, $newpath)
    {
        $this->checkBackupDirs();
        return parent::copy($path, $newpath);
    }
    
    /**
     * Ensures the backup structure is setup and proper
     */
    public function checkBackupDirs()
    {
        if( !$this->has('database') )
        {
            $this->createDir('database');
        }

        if( !$this->has('files') )
        {
            $this->createDir('files');
        }
    }
}