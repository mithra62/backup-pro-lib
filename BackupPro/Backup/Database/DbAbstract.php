<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/DbInterface.php
 */
 
namespace mithra62\BackupPro\Backup\Database;

use \mithra62\BackupPro\Backup\Database\DbInterface;
use mithra62\Traits\DateTime;
use mithra62\Traits\Log;

/**
 * Backup Pro - Database Backup Abstract Object
 *
 * Contains some helper methods and interface prototypes for backing up a database
 *
 * @package 	Backup\Database
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class DbAbstract implements DbInterface
{
    use DateTime, Log; 
    
    /**
     * The name of the Database Backup Engine
     * @var string
     */
    protected $name = null;
    
    /**
     * A description of the Database Backup Engine
     * @var string
     */
    protected $desc = null;    
    
    /**
     * A human readable shortname for the Database Backup Engine
     * @var string
     */
    protected $short_name = false;    
    
    /**
     * The full path to the completed database SQL file
     * @var string
     */
    protected $output_name = null;
    
    /**
     * Contains all the details about the backup
     * @var \mithra62\BackupPro\Backup\Database
     */
    protected $context = null;
    
    /**
     * The tables we're dealing with
     * @var array
     */
    protected $tables = array();
    
    /**
     * The CMD command to run for the engine
     * @var string
     */
    protected $engine_cmd = null;
    
    /**
     * The Command Line shell object
     * @var \mithra62\Shell
     */
    protected $shell = null;
    
    /**
     * Sets the database tables we're backing up
     * @param array $tables
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;
        return $this;
    }
    
    /**
     * Returns the tables we're backing up
     * @return \mithra62\BackupPro\Backup\Database\array
     */
    public function getTables()
    {
        return $this->tables;
    }
    
    /**
     * Returns the number of tables we're dealing with
     * @return int
     */
    public function getTotalTables()
    {
        return count($this->tables);
    }
    
    /**
     * Sets the context object
     * @param \mithra62\BackupPro\Backup\Database $context
     */
    public function setContext(\mithra62\BackupPro\Backup\Database $context)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Initializes the backup and writes the basics to the head
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function start()
    {
        $this->context->writeOut($this->getDumpFileHeader());
        $this->context->writeOut("SET FOREIGN_KEY_CHECKS = 0;".PHP_EOL);
        return $this;
    }
    
    /**
     * Adds any set SQL to the archive
     * 
     * NOTE that it's assumed this will only be called at the start of the backup process
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function archivePreSql()
    {
        if( $this->context->getArchivePreSql() )
        {
            $this->context->writeOut(implode(PHP_EOL, $this->context->getArchivePreSql()).PHP_EOL.PHP_EOL);
        }
        
        return $this;
    }
    
    /**
     * Executes any set SQL against the database
     * 
     * NOTE that it's assumed this will only be called at the start of the backup process
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function execPreSql()
    {
        if( $this->context->getExecutePreSql() )
        {
            foreach( $this->context->getExecutePreSql() AS $sql)
            {
                if( $sql != '' )
                {
                    $this->context->getBackup()->getDb()->query($sql);
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Adds any set SQL to the tail of the backup archive
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function archivePostSql()
    {
        return $this;
    }
    
    /**
     * Executes any remaining SQL at the tail end of the bacup process
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function execPostSql()
    {
        return $this;
    }
    
    /**
     * Returns an instance of the calling object
     * @return \mithra62\BackupPro\Backup\Database
     */
    protected function getContext()
    {
        return $this->context;
    }
    
    /**
     * Cleans up the end of the backup process
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function stop()
    {
        $this->context->writeOut("SET FOREIGN_KEY_CHECKS = 1;".PHP_EOL);
        $this->context->writeOut($this->getDumpFileFooter());
        return $this;
    }
    
    /**
     * Sets the backup output name wiht full path
     * @param string $name
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function setOutputName($name)
    {
        $this->output_name = $name;
        return $this;
    }
    
    /**
     * Returns the full path to the db archive file
     * @return \mithra62\BackupPro\Backup\Database\string
     */
    public function getOutputName()
    {
        return $this->output_name;
    }
    
    /**
     * Removes the whitespace from the given string
     * @param $string The string to remove white space from
     * @return \mithra62\BackupPro\Backup\Database\string
     */
    public function removeWhiteSpace($string)
    {
        $string = preg_replace('/\s*\n\s*/', ' ', $string) ;
        $string = preg_replace('/\(\s*/', '(', $string) ;
        $string = preg_replace('/\s*\)/', ')', $string) ;
        return $string;
    }
    
    /**
     * Adds a SQL Comment to the dump
     * @param string $comment
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function writeComment($comment)
    {
        $this->context->writeOut("-- ".$comment.PHP_EOL);
        return $this;
    }
    
    /**
     * Adds a SQL Comment to the dump
     * @param string $comment
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function writeCommentBlock($comment)
    {
        $this->context->writeOut("-- ".PHP_EOL);
        $this->context->writeOut("-- ".$comment.PHP_EOL);
        $this->context->writeOut("-- ".PHP_EOL);
        return $this;
    }
    
    /**
     * Returns the details the db dump header should contains
     * @return string
     */
    private function getDumpFileHeader()
    {
        $details = $this->getContext()->getBackup()->getDbInfo();
        // Some info about software, source and time
        $header = "-- Backup Pro 3 Database Archive" . PHP_EOL .
        "--" . PHP_EOL .
        "-- Backup Engine: ".$this->getName()." Host: {$details['host']}\tDatabase: {$details['database']}" . PHP_EOL .
        "-- ------------------------------------------------------" . PHP_EOL;
        //$header .= "-- Server version \t" . $this->version . PHP_EOL;
        
        $header .= "-- Date: " . $this->getDt()->now()->format('r') . PHP_EOL . PHP_EOL;
        
        return $header;
    }
    
    /**
     * Returns footer for dump file
     * @return string
     */
    private function getDumpFileFooter()
    {
        $footer = PHP_EOL.PHP_EOL.'-- Dump completed on: ' . $this->getDt()->now()->format('r').PHP_EOL;
        return $footer;
    }    
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::getEngineDetails()
     */
    public function getEngineDetails()
    {
        return array(
            'name' => $this->getName(),
            'short_name' => $this->getShortName(),
            'desc' => $this->getDescription()
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getShortName()
     */
    public function getShortName()
    {
        return $this->short_name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->desc;
    }

    /**
     * Sets the command the engine will need to run on the CLI
     * @param string $cmd
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function setEngineCmd($cmd = null)
    {
        $this->engine_cmd = $cmd;
        return $this;
    }
    
    /**
     * Returns the engine CLI comman
     * @return string
     */
    public function getEngineCmd()
    {
        return trim($this->engine_cmd);
    }    
    
    /**
     * Sets the shell object
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
     * @return \mithra62\Shell
     */
    public function getShell()
    {
        return $this->shell;
    }
}