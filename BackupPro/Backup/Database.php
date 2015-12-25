<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database.php
 */
namespace mithra62\BackupPro\Backup;

use \mithra62\BackupPro\Exceptions\Backup\DatabaseException;

/**
 * Backup Pro - Database Backup Object
 *
 * Contains the logic for executing a database backup
 *
 * @package Backup\Database
 * @author Eric Lamb <eric@mithra62.com>
 */
class Database extends AbstractBackup
{

    /**
     * The database object
     * 
     * @var \mithra62\Db
     */
    protected $db = null;

    /**
     * The backup engine we want to use for backing up the database
     *
     * MUST coorespond with an object in the Database directory
     * 
     * @var string
     */
    protected $engine = 'php';
    
    /**
     * The database backup engines available to the system
     * 
     * @var array
     */
    protected $available_engines = array();

    /**
     * The tables to not include in the archive
     * 
     * @var array
     */
    protected $ignore_tables = array();

    /**
     * The table names we don't want to include data for in the archive
     * 
     * @var array
     */
    protected $ignore_table_data = array();

    /**
     * Any SQL to include at the beginning of the archive
     * It should be 1 statement per line
     * 
     * @var string
     */
    protected $archive_pre_sql = null;

    /**
     * Any SQL to include at the end of the archive
     * It should be 1 statement per line
     * 
     * @var string
     */
    protected $archive_post_sql = null;

    /**
     * Any SQL to execute before the backup starts
     * It should be 1 statement per line
     * 
     * @var string
     */
    protected $execute_pre_sql = null;

    /**
     * Any SQL to execute once the backup ends
     * It should be 1 statement per line
     * 
     * @var string
     */
    protected $execute_post_sql = null;

    /**
     * The file handle for the backup output
     * 
     * @var handle
     */
    private $output = null;

    /**
     * The full path to the output dump
     * 
     * @var string
     */
    protected $output_path_name = null;

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
     * The tables we're working with
     * 
     * @var array
     */
    protected $tables = array();

    /**
     * How many statements we want to compile into 1 INSERT command
     * 
     * @var int
     */
    protected $sql_group_by = 250;

    /**
     * A table based group by
     *
     * Format should be $table => $group_by_limit
     * 
     * @var array
     */
    protected $table_sql_group_by = array();

    /**
     * Executes the database backup
     * 
     * @param string $database
     *            The name of the database we're backing up
     * @param string $file_name
     *            The filename to save the backup as
     * @return string The full path to the completed raw SQL file
     */
    public function backup($database, $file_name)
    {
        // start it up
        $path = $this->backup->startTimer()
            ->getStorage()
            ->getDbBackupNamePath($file_name);
        $progress = $this->backup->getProgress();
        $engine = $this->setOutputName($path)
            ->getEngine()
            ->start()
            ->execPreSql()
            ->archivePreSql();
        $tables = $this->tables = $this->backup->getDb()->getTableStatus();
        $engine->setSqlGroupBy($this->getSqlGroupBy())
            ->setTableSqlGroupBy($this->getTableSqlGroupBy())
            ->setEngineCmd($this->getEngineCmd())
            ->setShell($this->getShell())
            ->setTables($tables);
        
        // no go through the tables and back them up
        $count = 1;
        $total = count($tables);
        $progress->writeLog('backup_progress_bar_start', $total, 0);
        foreach ($tables as $table) {
            if ($table['Engine'] == '' || $table['Comment'] == 'View') {
                continue; // we have a View most likely or something else silly
            }
            
            if (count($this->getIgnoreTables()) >= 1 && in_array(trim($table['Name']), $this->getIgnoreTables())) {
                $engine->writeCommentBlock('Skipping ' . $table['Name'] . ' due to configuration');
                continue;
            }
            
            $progress->writeLog('backup_progress_bar_table_start' . $table['Name'], $total, $count);
            $engine->writeCommentBlock('Table Data For: ' . $table['Name'] . ' (' . $table['Rows'] . ' Rows)');
            $include_data = true;
            
            if (count($this->getIgnoreTableData()) >= 1 && in_array(trim($table['Name']), $this->getIgnoreTableData())) {
                // we're just grabbing the structure here
                $statement = $this->getBackup()
                    ->getDb()
                    ->getCreateTable($table['Name'], true);
                $statement = $this->removeWhiteSpace($statement);
                
                $this->writeOut('SET sql_notes = 0;      -- Temporarily disable the "Table already exists" warning' . PHP_EOL);
                $this->writeOut($statement . ";" . PHP_EOL);
                $this->writeOut('SET sql_notes = 1;      -- And then re-enable the warning again' . PHP_EOL);
            } else {
                // pass to engine and pull the table data
                $engine->backupTable($table['Name']);
            }
            
            $progress->writeLog('backup_progress_bar_table_stop' . $table['Name'], $total, $count);
            $count ++;
        }
        
        // post backup cleanup
        $engine->archivePostSql()
            ->execPostSql()
            ->stop();
        $this->closeOutput();
        
        $progress->writeLog('backup_progress_bar_database_stop', $total, $count);
        return $path;
    }

    /**
     * Writes out the initial details meta file for the backup
     * 
     * @param \mithra62\BackupPro\Backup\Details $details            
     * @param string $file_path
     *            The full path to the backup file
     * @param string $backup_method
     *            The method used for the backup (needed for restore options)
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function writeDetails(\mithra62\BackupPro\Backup\Details $details, $file_path, $backup_method = 'php')
    {
        $path_parts = pathinfo($file_path);
        $details_path = $path_parts['dirname'];
        $file_name = $path_parts['basename'];
        
        $details->createDetailsFile($file_name, $details_path);
        
        $base_details = array(
            'time_taken' => $this->getBackup()->getBackupTime(),
            'created_date' => $this->getNow(),
            'database_backup_type' => $backup_method,
            'max_memory' => memory_get_peak_usage(),
            'file_size' => filesize($file_path)
        );
        $details->addDetails($file_name, $details_path, $base_details);
        
        if ($this->getTables()) {
            $meta_details = array();
            $uncompressed_size = 0;
            foreach ($this->getTables() as $meta) {
                if ((count($this->getIgnoreTables()) >= 1 && in_array(trim($meta['Name']), $this->getIgnoreTables())) || (count($this->getIgnoreTableData()) >= 1 && in_array(trim($meta['Name']), $this->getIgnoreTableData()))) {
                    $meta_details[] = array(
                        'Name' => $meta['Name'],
                        'Rows' => 0,
                        'Avg_row_length' => 0,
                        'Data_length' => 0,
                        'Auto_increment' => $meta['Auto_increment']
                    );
                } else {
                    $uncompressed_size = $uncompressed_size + $meta['Data_length'];
                    $meta_details[] = array(
                        'Name' => $meta['Name'],
                        'Rows' => $meta['Rows'],
                        'Avg_row_length' => $meta['Avg_row_length'],
                        'Data_length' => $meta['Data_length'],
                        'Auto_increment' => $meta['Auto_increment']
                    );
                }
            }
            
            $details->addDetails($file_name, $details_path, array(
                'details' => $meta_details,
                'item_count' => count($meta_details),
                'uncompressed_size' => $uncompressed_size
            ));
        }
        
        return $this;
    }

    /**
     * Sets the backup file name
     * 
     * @param string $name            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setOutputName($name)
    {
        $this->output_path_name = $name;
        return $this;
    }

    /**
     * Returns the name the database backup should be
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getOutputName()
    {
        return $this->output_path_name;
    }

    /**
     * Closes the file output writer
     */
    public function closeOutput()
    {
        fclose($this->getOutput());
    }

    /**
     * Returns the file output handle
     * 
     * @param string $path            
     */
    public function getOutput($path = null)
    {
        if (is_null($this->output)) {
            if ($path == '') {
                $path = $this->getOutputName();
            }
            
            $this->output = fopen($path, "w");
        }
        
        return $this->output;
    }

    /**
     * Write a SQL statement to the backup file.
     * 
     * @param string $s
     *            The string to be written.
     * @access private
     */
    public function writeOut($s)
    {
        if ($this->getOutput() === null) {
            echo $s;
        } else {
            fputs($this->getOutput(), $this->toUtf8($s));
        }
    }

    /**
     * Sets the database backup method we want to use
     * 
     * @param string $engine            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setEngine($engine = 'php')
    {
        if(!array_key_exists($engine, $this->getAvailableEngines())) {
            throw new \InvalidArgumentException('Unknown backup engine "'.$engine.'"! ');
        }
        
        $this->engine = $engine;
        return $this;
    }

    /**
     * Returns an instance of the backup engine we're using
     * 
     * @return Ambigous <\mithra62\BackupPro\Backup\Database\DbInterface, unknown>
     */
    public function getEngine()
    {
        $class = "\\mithra62\\BackupPro\\Backup\\Database\\Engines\\" . ucfirst($this->engine);
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
        if(!$this->available_engines) {
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
                $class = "\\mithra62\\BackupPro\\Backup\\Database\\Engines\\" . $name;
                if (class_exists($class)) {
                    $obj = new $class();
                    if ($obj instanceof Database\DbInterface) {
                        $engines[$obj->getShortName()] = $obj->getEngineDetails();
                    }
                }
            }
            
            $d->close();
            chdir($old_cwd);
            $this->available_engines = $engines;
        }
        
        return $this->available_engines;
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
     * Sets the tables to ignore during backup
     * 
     * @param array $tables            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setIgnoreTables(array $tables = array())
    {
        $_tables = array();
        foreach ($tables as $k => $v) {
            if (trim($v) != '') {
                $_tables[] = trim($v);
            }
        }
        $this->ignore_tables = $_tables;
        return $this;
    }

    /**
     * Returns the tables to ignore during backup
     * 
     * @return \mithra62\BackupPro\Backup\array
     */
    public function getIgnoreTables()
    {
        return $this->ignore_tables;
    }

    /**
     * Sets the table data we want to exclude from the backup
     * 
     * @param array $tables            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setIgnoreTableData(array $tables = array())
    {
        $_tables = array();
        foreach ($tables as $k => $v) {
            if (trim($v) != '') {
                $_tables[] = trim($v);
            }
        }
        $this->ignore_table_data = $_tables;
        return $this;
    }

    /**
     * Removes the whitespace from the given string
     * 
     * @param $string The
     *            string to remove white space from
     * @return \mithra62\BackupPro\Backup\Database\string
     */
    public function removeWhiteSpace($string)
    {
        $string = preg_replace('/\s*\n\s*/', ' ', $string);
        $string = preg_replace('/\(\s*/', '(', $string);
        $string = preg_replace('/\s*\)/', ')', $string);
        return $string;
    }

    /**
     * Returns the tables we don't want to include data from
     * 
     * @return \mithra62\BackupPro\Backup\array
     */
    public function getIgnoreTableData()
    {
        return $this->ignore_table_data;
    }

    /**
     * Sets what SQL to execute once the backup ends
     * 
     * @param string $sql            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setExecutePostSql($sql = null)
    {
        $this->execute_post_sql = $sql;
        return $this;
    }

    /**
     * Returns the SQL to execute once the backup ends
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getExecutePostSql()
    {
        return $this->execute_post_sql;
    }

    /**
     * Sets what SQL to execute before the backup starts
     * 
     * @param string $sql            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setExecutePreSql($sql = null)
    {
        $this->execute_pre_sql = $sql;
        return $this;
    }

    /**
     * Returns the SQL to execute before the backup starts
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getExecutePreSql()
    {
        return $this->execute_pre_sql;
    }

    /**
     * Sets the SQL to include at the top of the archive
     * 
     * @param string $sql            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setArchivePostSql($sql = null)
    {
        $this->archive_post_sql = $sql;
        return $this;
    }

    /**
     * Returns the SQL to include at the top of the archive
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getArchivePostSql()
    {
        return $this->archive_post_sql;
    }

    /**
     * Sets the SQL to include at the top of the archive
     * 
     * @param string $sql            
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setArchivePreSql($sql = null)
    {
        $this->archive_pre_sql = $sql;
        return $this;
    }

    /**
     * Returns the SQL to include at at the top of the archive
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getArchivePreSql()
    {
        return $this->archive_pre_sql;
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
     * Returns an array of the tables being processed
     * 
     * @return \mithra62\BackupPro\Backup\array
     */
    public function getTables()
    {
        return $this->tables;
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

    /**
     * Sets how many rows to include per INSERT statement on recovery
     * 
     * @param int $number            
     * @return \mithra62\BackupPro\Backup\Database\Php
     */
    public function setSqlGroupBy($number)
    {
        $this->sql_group_by = $number;
        return $this;
    }

    /**
     * Returns the total number of rows to group INSERT statements by
     * 
     * @return \mithra62\BackupPro\Backup\Database\int
     */
    public function getSqlGroupBy()
    {
        return $this->sql_group_by;
    }

    /**
     * Sets the configuration for how many rows to chunk per table (if configured)
     * 
     * @param array $config            
     * @return \mithra62\BackupPro\Backup\Database\Php
     */
    public function setTableSqlGroupBy(array $config)
    {
        $this->table_sql_group_by = $config;
        return $this;
    }

    /**
     * Returns the total number of rows to group INSERT statements by
     * 
     * @return \mithra62\BackupPro\Backup\Database\int
     */
    public function getTableSqlGroupBy()
    {
        return $this->table_sql_group_by;
    }
}