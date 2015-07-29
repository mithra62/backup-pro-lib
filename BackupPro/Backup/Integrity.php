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

/**
 * Backup Pro - Integrity Agent Object
 *
 * Performs checks against backups to ensure they're stable and able to restore
 *
 * @package 	Backup\IntegrityAgent
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Integrity
{
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
        $this->settings = ee()->backup_pro->get_settings();
    }
    
    /**
     * Checks the integrity of the backup in $path
     * @param string $path
     * @param string $type
     * @param string $force
     */
    public function checkBackup(array $backup_info, $type = 'database')
    {
        $path = $backup_info['file_name'];
        switch($type)
        {
            case 'database':
                $path = realpath(rtrim(ee()->backup_pro->backup_db_dir, '/').'/'.$path);
                return $this->checkDatabaseBackup($path);
                break;
                	
            case 'files':
                $path = realpath(rtrim(ee()->backup_pro->backup_files_dir, '/').'/'.$path);
                return $this->checkFilesBackup($path);
                break;
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
            $cache_path = rtrim(APPPATH, '/').'/cache';
            $success = false;
            if( is_dir($cache_path) && is_writable($cache_path) && is_readable($cache_path) )
            {
                $folder = 'bp_'.md5(microtime(true));
                $temp = $cache_path.'/'.$folder;
                mkdir($temp);
                $zip = new ZipArchive;
                $backup_archive = $zip->open($path);
                if($backup_archive === true)
                {
                    $zip->extractTo($temp);
                    $zip->close();
                    $success = true;
                }
    
                ee()->backup_pro->delete_dir($temp);
                return $success;
            }
        }
    }
    
    /**
     * Checks the existing backups on the system and ensure's things are kosher
     */
    public function monitorBackupState()
    {
        $errors = ee()->backup_pro->error_check();
        if(isset($errors['backup_state_db_backups']) || isset($errors['backup_state_files_backups']))
        {
            //we have a winner! start the notification process
            ee()->load->library('Backup_pro_notify', null, 'notify');
            $last_notified = $this->settings['backup_missed_schedule_notify_email_last_sent'];
            $next_notified = mktime(date('G', $last_notified)+$this->settings['backup_missed_schedule_notify_email_interval'], date('i', $last_notified), 0, date('n', $last_notified), date('j', $last_notified), date('Y', $last_notified));
            	
            if(time() > $next_notified)
            {
                ee()->notify->send_backup_state($errors);
                ee()->backup_pro_settings->update_setting('backup_missed_schedule_notify_email_last_sent', time());
            }
        }
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
    
    public function getCronCommands($module_name)
    {
        $action_id = $this->getCronAction($module_name);
        $url = ee()->config->config['site_url'].'?ACT='.$action_id;
        return array(
            'verify_backup_stability' => array('url' => $url, 'cmd' => '0 * * * * * curl "'.$url.'"')
        );
    }
    
    public function getCronAction($module_name)
    {
        ee()->load->dbforge();
        ee()->db->select('action_id');
        $query = ee()->db->get_where('actions', array('class' => $module_name, 'method' => 'integrity_cron'));
        return $query->row('action_id');
    }
}