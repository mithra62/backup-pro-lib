<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup.php
 */
 
namespace mithra62\BackupPro;

use mithra62\BackupPro\Exceptions\Backup\DatabaseException;
use mithra62\BackupPro\Exceptions\Backup\FilesException;
use mithra62\BackupPro\Exceptions\Backup\ProgressException;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\Exceptions\CompressException;
use mithra62\Exceptions\RegexException;

/**
 * Backup Pro - Backup Object
 *
 * Contains the methods for executing the backup
 *
 * @package 	Backup
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Backup
{
    /**
     * Where we want to store backups
     * @var string
     */
    protected $storage_path = null;
    
    /**
     * The database object
     * @var \mithra62\Db
     */
    protected $db = null;
    
    /**
     * The Backup\Storage object
     * @var Backup\Storage
     */
    protected $storage = null;

    /**
     * The progress object
     * @var Backup\Progress
     */
    protected $progress = null;
    
    /**
     * The Database Backup Engine 
     * @var Backup\Database
     */
    protected $database = null;
    
    /**
     * The File Backup Engine
     * @var Backup\Files
     */
    protected $file = null;

    /**
     * The Compression object
     * @var \mithra62\Compress
     */
    protected $compress = null;
    
    /**
     * The Backup Details obect
     * @var Backup\Details
     */
    protected $details = null;
    
    /**
     * The database connection details
     * @var array
     */
    protected $db_info = array();
    
    /**
     * The execution start time in milliseconds
     * @var int
     */
    protected $timer_start = 0;
    
    /**
     * The execution stop time in milliseconds
     * @var int
     */
    protected $timer_stop = 0;

    /**
     * The Services array
     * @var array
     */
    private $services = array();
    
    /**
     * Set it up
     * @param \mithra62\Db $db
     */
    public function __construct(\mithra62\Db $db)
    {
        $this->db = $db;
    }
    
    /**
     * Sets the timer start time
     */
    public function startTimer()
    {
        $this->timer_start = microtime(true);
        return $this;
    }
    
    /**
     * Sets the timer stop time
     */
    public function stopTimer()
    {
        $this->timer_stop = microtime(true);
        return $this;
    }
    
    /**
     * Returns the total backup time
     * @return number
     */
    public function getBackupTime()
    {
        $this->stopTimer();
        return $this->timer_stop - $this->timer_start;
    }
    
    /**
     * Sets the backup storage path
     * @param string $path
     * @return \mithra62\BackupPro\Backup
     */
    public function setStoragePath($path)
    {
        $this->storage_path = $path;
        return $this;
    }
    
    /**
     * returns the path to store backups
     * @return \mithra62\BackupPro\string
     */
    public function getStoragePath()
    {
        return $this->storage_path;
    }
    
    /**
     * Sets the database details for backing up a db
     * @param array $db_info
     * @see \mithra62\Db
     * @return \mithra62\BackupPro\Backup
     */
    public function setDbInfo(array $db_info)
    {
        $this->db_info = $db_info;
        return $this;
    }
    
    /**
     * Returns the database details
     * @return \mithra62\BackupPro\array
     */
    public function getDbInfo()
    {
        return $this->db_info;
    }
    
    /**
     * Wrapper to backup a database
     * @param string $database The name of the database to backup
     * @param array $options The various options and details (mithra62\Settings)
     * @param \mithra62\Shell $shell
     * @return string The path to where the backup is stored locally
     * @throws \mithra62\BackupPro\Exceptions\Backup\DatabaseException
     * @throws \mithra62\Exceptions\CompressException
     * @throws \mithra62\BackupPro\Exceptions\Backup\StorageException
     * @throws \Exception
     */
    public function database($database, array $options, \mithra62\Shell $shell)
    {
        try 
        {
            $file_name = $this->getStorage()->makeDbFilename($options['db_backup_method'], $database);
            $db = $this->getDatabase()->setBackup($this)
                                      ->setIgnoreTableData( $options['db_backup_ignore_table_data'] )
                                      ->setIgnoreTables( $options['db_backup_ignore_tables'] )
                                      ->setArchivePostSql( $options['db_backup_archive_post_sql'] )
                                      ->setArchivePreSql( $options['db_backup_archive_pre_sql'] )
                                      ->setExecutePostSql( $options['db_backup_execute_post_sql'] )
                                      ->setExecutePreSql( $options['db_backup_execute_pre_sql'] )
                                      ->setEngine( $options['db_backup_method'] )
                                      ->setEngineCmd( $options['mysqldump_command'] )
                                      ->setShell($shell);
            
            $backup_file = $db->backup($database, $file_name);
            $compressed_file = $this->getCompress()->setKeepOriginal(false)->archiveSingle($backup_file);
            $db->writeDetails($this->getDetails(), $compressed_file, $options['db_backup_method']);
            
            $this->getStorage()->save($compressed_file, $options['storage_details'], 'database', $this->getDetails());
            
            @unlink($compressed_file); //remove the original
            
            return $compressed_file;
            
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
     * Wrapper to perform a file backup
     * @param array $options The Settings array
     * @param \mithra62\Files $file The File object for handling operations
     * @param \mithra62\Regex $regex
     * @throws FilesException
     * @throws CompressException
     * @throws \Exception
     */
    public function files(array $options, \mithra62\Files $file, \mithra62\Regex $regex)
    {
        try
        {
            $file_name = $this->getStorage()->makeFileFilename();
            $file_backup = $this->getFile()->setRegex($regex)->setBackup($this)->setFile($file)
                                ->setExcludePaths($options['exclude_paths'])
                                ->setBackupPaths($options['backup_file_location']);
            
            $compressed_file = $file_backup->backup($file_name, $this->getCompress());
            $file_backup->writeDetails($this->getDetails(), $compressed_file);
            
            $this->getStorage()->save($compressed_file, $options['storage_details'], 'files', $this->getDetails());
            
            @unlink($compressed_file); //remove the original
            return true;
        }
        catch(FilesException $e)
        {
            $e->logException($e);
            throw new FilesException($e->getMessage());
        }
        catch(CompressException $e)
        {
            $e->logException($e);
            throw new CompressException($e->getMessage());
        }
        catch(RegexException $e)
        {
            $e->logException($e);
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Progress
     */
    public function getProgress()
    {
        if( is_null($this->progress) )
        {
            $this->progress = new Backup\Progress( $this->getStoragePath() );  
        }
        
        return $this->progress;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function getDatabase()
    {
        if( is_null($this->database) )
        {
            $this->database = new Backup\Database();  
        }
        
        return $this->database;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function getFile()
    {
        if( is_null($this->file) )
        {
            $this->file = new Backup\Files();  
        }
        
        return $this->file;
    }
    
    /**
     * Returns the database object to use for backing up
     * @return \mithra62\Db
     */
    public function getDb()
    {
        return $this->db;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Storage
     */
    public function getStorage()
    {
        if( is_null($this->storage) )
        {
            $this->storage = new Backup\Storage( $this->getStoragePath() ); 
            $this->storage->setServices( $this->getServices() );
        }
        
        return $this->storage;
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
     * Returns an instance of the Backup\Details object
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function getDetails()
    {
        if( is_null($this->details) )
        {
            $this->details = new Backup\Details();
        }
        
        return $this->details;
    }
    
    /**
     * Sets the Services array
     * @param array $services
     */
    public function setServices(\Pimple\Container $services)
    {
        $this->services = $services;
        return $this;
    }
    
    /**
     * Returns the Serivices array
     */
    public function getServices()
    {
        return $this->services;
    }    
}