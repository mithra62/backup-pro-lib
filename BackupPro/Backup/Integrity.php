<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/IntegrityAgent.php
 */
 
namespace mithra62\BackupPro\Backup;
use mithra62\Traits\DateTime;

/**
 * Backup Pro - Integrity Agent Object
 *
 * Performs checks against backups to ensure they're stable and able to restore
 *
 * @package 	Backup
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Integrity
{
    use DateTime;
    
    protected $storage = null;
    
    protected $compress = null;
    
    /**
     * The Files object
     * @var \mithra62\Files
     */
    protected $file = null;
    
    /**
     * The database connection info
     * @var array
     */
    private $db_conf = array();
    
    /**
     * @ignore
    */
    public function __construct()
    {
        
    }
    
    /**
     * Sets the Files instances
     * @param \mithra62\Files $file
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setFile(\mithra62\Files $file)
    {
        $this->file = $file;
        return $this;
    }
    
    /**
     * Returns an instance of the Files object
     * @return \mithra62\Files
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Returns an instance of the Compress object
     * @return \mithra62\Compress
     */
    public function getCompress()
    {
        if( is_null($this->compress) )
        {
            $this->compress = new \mithra62\Compress;
        }
        
        return $this->compress;
    }
    
    /**
     * Sets an instance of the Storage object
     * @param Storage $storage
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
        return $this;
    }
    
    public function getStorage()
    {
        return $this->storage;
    }
    
    /**
     * Checks the integrity of the backup in $path
     * @param string $path
     * @param string $type
     * @param string $force
     */
    public function checkBackup(array $backup_info, $type = 'database')
    {
        foreach($backup_info['storage_locations'] AS  $location)
        {
            if( $location['obj']->canDownload() )
            {
                $path = $location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']);
                if( file_exists($path) && is_readable($path) )
                {
                    switch($type)
                    {
                        case 'database':
                            return $this->checkDatabaseBackup($path);
                            break;
                            	
                        case 'files':
                            return $this->checkFilesBackup($path);
                            break;
                    }
                }
            }
        }
    }
    
    /**
     * Checks the integrity of a database backup
     * @param string $path
     */
    public function checkDatabaseBackup($path)
    {
        if(file_exists($path) && $this->getDbConf())
        {
            //we have to check if we can even USE the damn db for testing the database
            $db = FactoryDB::factory($this->db_conf['user'], $this->db_conf['pass'], $this->db_conf['db_name'], $this->db_conf['host'], dmDB_MySQL) ;
            $db->connect();
            if($db->hasErrors())
            {
                $db->showErrors();
                exit;
            }
            	
            $temp = $this->settings['backup_store_location'].'/database/tmp';
            if(!file_exists($temp))
            {
                mkdir($temp);
            }
            	
            $success = false;
            	
            $path = ee()->backup_pro->unzip_db_backup($path, $temp);
            if(ee()->backup_pro_sql_backup->restore($path, $this->getDbConf()))
            {
                $this->clearTestDb();
                $success = true;
            }
            ee()->backup_pro->delete_dir($temp);
            return $success;
        }
    }
    
    /**
     * Verifies a file backup can be unzipped
     * @param string $path
     * @return boolean
     */
    public function checkFilesBackup($path)
    {
        if(file_exists($path))
        {
            $storage = $this->getStorage();
            $working_dir = $storage->getBackupDir();
            $success = false;
            $compress = $this->getCompress();
            if( is_dir($working_dir) && is_writable($working_dir) && is_readable($working_dir) )
            {
                $folder = 'bp_'.md5(microtime(true));
                $temp = $working_dir.'/'.$folder;
                mkdir($temp);
                
                $backup_archive = $compress->extract($path, $temp);
                if( $backup_archive === true )
                {
                    $success = true;
                }
    
                $this->getFile()->deleteDir($temp, true);
            }
            return $success;
        }
    }
    
    /**
     * Checks the existing backups on the system and ensure's things are kosher
     * @param array $backup_meta
     * @param array $settings
     * @return multitype:boolean
     */
    public function monitorBackupState(array $backup_meta, array $settings)
    {
        $errors = array();
        //now let's check to see if we have a backup for each configured timeframe
        if( $settings['db_backup_alert_threshold'] >= 1 )
        {
            if( !empty($backup_meta['database']['newest_backup_taken_raw']) )
            {
                $db_check_hours = mktime(0,0,0,date('m'), date('d')-$settings['db_backup_alert_threshold'], date('Y'));
                if($backup_meta['database']['newest_backup_taken_raw'] < $db_check_hours)
                {
                    $errors['backup_state_db_backups'] = TRUE;
                }
            }
        }
        
        if( $settings['file_backup_alert_threshold'] >= 1 )
        {
            if( !empty($backup_meta['files']['newest_backup_taken_raw']) )
            {
                $file_check_hours = mktime(0,0,0,date('m'), date('d')-$settings['file_backup_alert_threshold'], date('Y'));
                if($backup_meta['files']['newest_backup_taken_raw'] < $file_check_hours)
                {
                    $errors['backup_state_files_backups'] = TRUE; 
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Sets the database connection info for verifying database backups
     * @param array $db_info
     */
    public function setDbConf(array $db_conf)
    {
        $this->db_conf = $db_conf;
    }
    
    /**
     * Returns the database connection details for verifying the database.
     * Also runs checks to ensure we're NOT using the main database
     * @return array
     */
    public function getDbConf()
    {
        if( !empty($this->db_conf['db_name']) && $this->db_conf['db_name'] != ee()->db->database)
        {
            return $this->db_conf;
        }
    
        return array();
    }
    
    /**
     * Clears all the tables from the testing database
     * @return boolean
     */
    private function clearTestDb()
    {
        $db = FactoryDB::factory($this->db_conf['user'], $this->db_conf['pass'], $this->db_conf['db_name'], $this->db_conf['host'], dmDB_MySQL) ;
        $db->connect();
        if($db->hasErrors())
        {
            $db->showErrors();
            exit;
        }
    
        $sql = "SHOW TABLE STATUS ";
        $db->queryConstant($sql) ;
    
        while ($theDataRow =& $db->fetchAssoc())
        {
            $drop_db = FactoryDB::factory($this->db_conf['user'], $this->db_conf['pass'], $this->db_conf['db_name'], $this->db_conf['host'], dmDB_MySQL) ;
            $drop_db->queryConstant(sprintf("DROP TABLE IF EXISTS `%s`; \n", $theDataRow['Name'])) ;
        }
         
        return true;
    }

    /**
     * Sets an instance of the calling object
     * @param object $context
     * @return \mithra62\BackupPro\Backups
     */
    public function setContext(\mithra62\BackupPro\Backups $context)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backups
     */
    public function getContext()
    {
        return $this->context;
    }
}