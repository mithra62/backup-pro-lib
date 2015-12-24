<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Restore/Database.php
 */
namespace mithra62\BackupPro\Restore;

use \mithra62\BackupPro\Exceptions\Backup\DatabaseException;

/**
 * Backup Pro - Database Restore Object
 *
 * Contains the logic for executing a database restoration
 *
 * @package Restore\Database
 * @author Eric Lamb <eric@mithra62.com>
 */
class Database extends AbstractRestore
{

    /**
     * The database object
     * 
     * @var \mithra62\Db
     */
    protected $db = null;

    /**
     * The backup engine we want to use for restoring up the database
     *
     * MUST coorespond with an object in the Database directory
     * 
     * @var string
     */
    protected $engine = 'php';

    /**
     * The CMD command to run for the engine
     * 
     * @var string
     */
    protected $engine_cmd = null;

    /**
     * The Command Line shell object
     * 
     * @var \mithra62\Shell
     */
    protected $shell = null;

    /**
     * Executes the database backup
     * 
     * @param string $database
     *            The name of the database we're backing up
     * @param string $file_name
     *            The filename to save the backup as
     * @return string The full path to the completed raw SQL file
     */
    public function restore($database, $file_name)
    {
        // start it up
        $progress = $this->restore->getProgress();
        
        $backup_info = $this->getBackupInfo();
        $engine = $this->getEngine();
        if (! $engine->canRestore($backup_info['database_backup_type'])) {
            throw new DatabaseException("You can't restore this backup using the \"" . $engine->getName() . "\" engine since it was backed up using the \"" . $backup_info['database_backup_type'] . "\" backup engine!");
        }
        
        $engine->setEngineCmd($this->getEngineCmd())
            ->setShell($this->getShell());
        return $engine->restore($database, $file_name);
    }

    /**
     * Sets the database backup method we want to use
     * 
     * @param string $engine            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setEngine($engine = 'php')
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * Returns an instance of the backup engine we're using
     * 
     * @return Ambigous <\mithra62\BackupPro\Restore\Database\DbInterface, unknown>
     */
    public function getEngine()
    {
        $class = "\\mithra62\\BackupPro\\Restore\\Database\\Engines\\" . ucfirst($this->engine);
        if (class_exists($class)) {
            $obj = new $class();
            if ($obj instanceof Database\DbInterface) {
                $obj->setContext($this);
                return $obj;
            }
        }
        
        throw new DatabaseException('Unknown database engine "' . $class . '"!');
    }

    /**
     * Returns an array of the available databaes backup engines
     * 
     * @throws DatabaseException
     * @return multitype:multitype:
     */
    public function getAvailableEngines()
    {
        $old_cwd = getcwd();
        chdir(dirname(__FILE__));
        $path = './Database/Engines';
        if (! is_dir($path)) {
            throw new DatabaseException("Engine Directory " . $path . " isn't a directory...");
        }
        
        $d = dir($path);
        $engines = array();
        while (false !== ($entry = $d->read())) {
            $name = ucfirst(str_replace('.php', '', $entry));
            $class = "\\mithra62\\BackupPro\\Restore\\Database\\Engines\\" . $name;
            if (class_exists($class)) {
                $obj = new $class();
                if ($obj instanceof Database\DbInterface) {
                    $engines[$obj->getShortName()] = $obj->getEngineDetails();
                }
            }
        }
        
        $d->close();
        chdir($old_cwd);
        return $engines;
    }

    /**
     * Returns a key/value pair of available database backup engines
     * 
     * @return multitype:\mithra62\BackupPro\Backup\multitype:
     */
    public function getAvailableEnginesOptions()
    {
        $engines = $this->getAvailableEngines();
        $return = array();
        foreach ($engines as $key => $engine) {
            $return[$key] = $engine['name'];
        }
        
        return $return;
    }

    /**
     * Sets the command the engine will need to run on the CLI
     * 
     * @param string $cmd            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setEngineCmd($cmd = null)
    {
        $this->engine_cmd = $cmd;
        return $this;
    }

    /**
     * Returns the engine CLI command
     * 
     * @return string
     */
    public function getEngineCmd()
    {
        return $this->engine_cmd;
    }

    /**
     * Sets the shell object
     * 
     * @param \mithra62\Shell $shell            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setShell(\mithra62\Shell $shell)
    {
        $this->shell = $shell;
        return $this;
    }

    /**
     * Returns an instance of the Shell object
     * 
     * @return \mithra62\Shell
     */
    public function getShell()
    {
        return $this->shell;
    }
}