<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Restore.php
 */
 
namespace mithra62\BackupPro;

use mithra62\BackupPro\Exceptions\Backup\DatabaseException;
use mithra62\BackupPro\Exceptions\Backup\ProgressException;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\Exceptions\CompressException;

/**
 * Backup Pro - Restore Object
 *
 * Handles restoring the system state
 *
 * @package 	Restore
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Restore extends Backup
{
    /**
     * The backup info array for the backup we want to restore
     * @var array
     */
    protected $backup_info = array();
    
    /**
     * Returns the total restoration time
     * @return number
     */
    public function getRestoreTime()
    {
        $this->stopTimer();
        return $this->timer_stop - $this->timer_start;
    }
    
    /**
     * Handles restoring a database
     * @param string $file The full path to the database archive file
     * @param array $backup The BackupPro data array
     * @param array $options Any specific optoins the drivers need
     * @param \mithra62\Shell $shell
     */
    public function database($database, $file_name, array $options, \mithra62\Shell $shell)
    {
        try
        {
            $file_details = pathinfo($file_name);
            $path = $file_details['dirname'].DIRECTORY_SEPARATOR.'tmp';
            if( !file_exists($path) )
            {
                @mkdir($path);
            }
            
            if( $this->getCompress()->extract($file_name, $path) )
            {
                $db = $this->getDatabase()->setRestore($this)
                                          ->setBackupInfo($this->getBackupInfo())
                                          ->setEngine( $options['db_restore_method'] )
                                          ->setEngineCmd( $options['mysqlcli_command'] )
                                          ->setShell($shell);
                
                $restore_file = $path.DIRECTORY_SEPARATOR.$file_details['filename'];
                if( $db->restore($database, $restore_file) )
                {
                
                }
                
                //remove unzipped 
                $services = $this->getServices();
                $services['files']->deleteDir($path, true, 1);    
                return true;
            }
        }
        catch(DatabaseException $e)
        {
            $e->logException($e);
            throw new DatabaseException($e->getMessage());
        }
        catch(CompressException $e)
        {
            $e->logException($e);
            throw new CompressException($e->getMessage());
        }
        catch(StorageException $e)
        {
            $e->logException($e);
            throw new StorageException($e->getMessage());
        }
        catch(ProgressException $e)
        {
            $e->logException($e);
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Handles restoring a backup onto the file system
     * @param array $backup The BackupPro data array
     * @param array $options
     * @param \mithra62\Files $file
     */
    public function files(array $options, \mithra62\Files $file, \mithra62\Regex $regex)
    {
        
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function getDatabase()
    {
        if( is_null($this->database) )
        {
            $this->database = new Restore\Database();  
        }
        
        return $this->database;
    }
    
    /**
     * Sets the Backup Info for the backup we want to restore
     * @param array $backup_info
     * @return \mithra62\BackupPro\Restore
     */
    public function setBackupInfo(array $backup_info)
    {
        $this->backup_info = $backup_info;
        return $this;
    }
    
    /**
     * Returns the Backup Info for the backup we want to restore
     * @return array
     */
    public function getBackupInfo()
    {
        return $this->backup_info;
    }
}