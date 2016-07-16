<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/Php.php
 */
namespace mithra62\BackupPro\Backup\Database\Engines;

use \mithra62\BackupPro\Backup\Database\DbAbstract;
use \mithra62\BackupPro\Db\Mysql;

/**
 * Backup Pro - PHP Backup Engine
 *
 * Performs a backup using native PHP
 *
 * @package Backup\Database\Engines
 * @author Eric Lamb <eric@mithra62.com>
 */
class Php extends DbAbstract
{

    /**
     * The name of the Database Backup Engine
     * 
     * @var string
     */
    protected $name = 'Php';

    /**
     * A human readable shortname for the Database Backup Engine
     * 
     * @var string
     */
    protected $short_name = 'php';

    /**
     * How many statements we want to compile into 1 INSERT command
     * 
     * @var int
     */
    protected $sql_group_by = 250;

    /**
     * The values for type of column definition
     * 
     * @var array
     */
    protected $mysql_data_type_hash = array(
        1 => 'tinyint',
        2 => 'smallint',
        3 => 'int',
        4 => 'float',
        5 => 'double',
        7 => 'timestamp',
        8 => 'bigint',
        9 => 'mediumint',
        10 => 'date',
        11 => 'time',
        12 => 'datetime',
        13 => 'year',
        16 => 'bit',
        // 252 is currently mapped to all text and blob types (MySQL 5.0.51a)
        253 => 'varchar',
        254 => 'char',
        246 => 'decimal'
    );

    /**
     * The DB object we're using for backup
     * 
     * @var Mysql
     */
    protected $db = null;

    /**
     * Returns an instance of the database object for the backup
     * 
     * @return \mithra62\BackupPro\Db\Mysql
     */
    public function getDb()
    {
        if (is_null($this->db)) {
            $this->db = $this->getContext()->getBackup()->getDb();
        }
        
        return $this->db;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::backup()
     * @ignore
     *
     */
    public function backupTable($table)
    {
        // first, the create statement
        $statement = $this->getDb()
            ->getCreateTable($table, false);
        $statement = $this->removeWhiteSpace($statement);
        
        // we want data so we're gonna want to drop the table
        $this->getContext()->writeOut(sprintf("DROP TABLE IF EXISTS `%s`;" . PHP_EOL, $table));
        $this->getContext()->writeOut($statement . ";" . PHP_EOL);
        $this->getContext()->writeOut(PHP_EOL);
        
        // now the data
        $this->writeCommentBlock('Data for ' . $table);
        $this->getTableData($table);
        
        $this->getContext()->writeOut(PHP_EOL);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::backup_procedure()
     * @ignore
     *
     */
    public function backupProcedure($procedure)
    {}

    /**
     * Backs up a table's data
     * 
     * @param string $table            
     */
    public function getTableData($table)
    {
        $db = clone ($this->getDb());
        $db->clear();
        $total_rows = $db->totalRows($table);
        $db->clear();
        if ($total_rows >= 1) {
            $total_pages = ceil($total_rows / $this->getSqlGroupBy());
            $column_data = $db->getColumns($table);
            
            $offset = 0;
            for ($i = 0; $i < $total_pages; $i ++) {
                $data = $db->query($this->buildSelectStatement($column_data, $table, $offset, $this->getSqlGroupBy()), true);
                
                $columns = '';
                $rows = array();
                foreach ($data AS $key => $value) {
                    if ($columns == '') {
                        $columns = '`' . implode('`, `', array_keys($value)) . '`';
                    }
                    
                    $the_data = array();
                    foreach ($value as $k => $v) {
                        $the_data[] = $this->prepareData($k, $v, $column_data);
                    }
                    
                    $rows[] = '(' . implode(', ', $the_data) . ')';
                }
                
                $line = implode(', ', $rows);
                $the_insert = sprintf("INSERT INTO `%s` (%s) VALUES %s ;\n", $table, $columns, $line);
                
                $this->getContext()->writeOut($the_insert);
                $this->getContext()->writeOut(PHP_EOL);
                
                $db->clear();
                $offset += $this->getSqlGroupBy();
            }
        }
    }

    /**
     * Creates the SELECT statement for use in the backup
     * 
     * @param array $column_data            
     * @param string $table            
     * @param int $offset            
     * @param string $group_by            
     * @return string
     */
    public function buildSelectStatement(array $column_data, $table, $offset, $group_by)
    {
        $columns = array();
        foreach ($column_data as $column) {
            $column_type = $this->determineColumnType($column['Type']);
            $class = "\\mithra62\\BackupPro\\Backup\\Database\\Engines\\Php\\Columns\\Bp" . $column_type;
            if (class_exists($class)) {
                $obj = new $class();
                if ($obj instanceof Php\Columns) {
                    $columns[] = $obj->getFieldName($column);
                } else {
                    $columns[] = $this->tickIt($column['Field']);
                }
            } else {
                $columns[] = $this->tickIt($column['Field']);
            }
        }
        
        return sprintf('SELECT ' . implode(',', $columns) . ' FROM %1$s LIMIT %2$s, %3$s', $table, $offset, $group_by);
    }

    /**
     * Abstracts preparing the data fro storage in the SQL backup file
     * 
     * @param string $column_name
     *            The name for the column in the table
     * @param string $value
     *            The value the column contains
     * @param array $column_data
     *            The data we're storing
     * @return string The string to use in the backup SQL string
     */
    public function prepareData($column_name, $value, array $column_data)
    {
        foreach ($column_data as $column) {
            if ($column['Field'] == $column_name) {
                
                $column_type = $this->determineColumnType($column['Type']);
                $class = "\\mithra62\\BackupPro\\Backup\\Database\\Engines\\Php\\Columns\\" . $column_type;
                $data = '';
                if (class_exists($class)) {
                    $obj = new $class();
                    if ($obj instanceof Php\Columns) {
                        $data = $obj->getFieldValue($value);
                    }
                }
                
                if ($data == '') {
                    if (is_null($value)) {
                        $data = 'NULL';
                    } elseif (!$obj instanceof Php\Columns) { //we have to escape strings
                        $data = "'" . $this->getContext()
                            ->getBackup()
                            ->getDb()
                            ->escape($value) . "'";
                    }
                }
                
                return $data;
            }
        }
    }

    /**
     * Takes the MysQL column Type value and parses out the alpha string for checking against an object
     * 
     * @param string $type            
     * @return string
     */
    protected function determineColumnType($type)
    {
        $parts = explode('(', $type);
        if (! empty($parts['0'])) {
            return $parts['0'];
        }
    }

    /**
     * Wraps a string in ticks for MySQL safety
     * 
     * @param string $string            
     * @return string
     */
    public function tickIt($string)
    {
        return '`' . $string . '`';
    }
}