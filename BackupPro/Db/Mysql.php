<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/Db/Mysql.php
 */
namespace mithra62\BackupPro\Db;

use mithra62\BackupPro\Exceptions\Db\MysqlException;

/**
 * Backup Pro - Mysql Object
 *
 * Abstracts interaction with Mysql
 *
 * @package BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Mysql extends AbstractDb
{

    /**
     * Sets up the defaults
     * 
     * @param string $dblogin
     *            The username to use for mysql
     * @param string $dbpass
     *            The password to use for mysql
     * @param string $dbname
     *            The name of the mysql database we want to use
     * @param string $dbhost
     *            The location of the mysql database
     */
    public function __construct($dblogin, $dbpass, $dbname, $dbhost = null)
    {
        parent::__construct($dblogin, $dbpass, $dbname, $dbhost);
    }

    /**
     * Returns the last query id
     * 
     * @access protected
     * @return reference to resource the query id of the last executed query.
     */
    public function getQueryid()
    {
        return $this->queryid;
    }

    /**
     * return the list of tables in the current database.
     * 
     * @return array the names of the tables in the current database.
     */
    public function showTables()
    {
        $theTableNames = array();
        $this->queryConstant('SHOW TABLES ;');
        while ($theTableName = & $this->fetchRow()) {
            $theTableNames[] = $theTableName[0];
        }
        
        return $theTableNames;
    }

    /**
     * return the SQL query to create the specified table.
     * 
     * @param
     *            string the name of the table.
     * @return string the SQL query to create the table.
     */
    public function &showCreateTable($theTableName)
    {
        $this->queryConstant(sprintf('SHOW CREATE TABLE `%s` ;', $theTableName));
        $theCreateQuery = $this->fetchRow();
        return $theCreateQuery[1];
    }

    /**
     * Abstract functions for database access.
     * Assumes that all MySQL database interfaces
     * are functionally available using the actual database.
     */
    public function affectedRows($dblink)
    {
        return mysqli_affected_rows($dblink);
    }

    public function close($dblink)
    {
        return mysqli_close($dblink);
    }

    public function dBConnect($dbhost, $dblogin, $dbpass, $database)
    {
        return mysqli_connect($dbhost, $dblogin, $dbpass, $database);
    }

    public function dataSeek($queryid, $row)
    {
        return mysqli_data_seek($queryid, $row);
    }

    public function error()
    {
        return mysqli_error($this->getDbLink());
    }

    public function fetchArray($queryid)
    {
        return mysqli_fetch_array($queryid);
    }

    public function dbFetchAssoc($queryid)
    {
        if ($queryid instanceof \mysqli_result) {
            return mysqli_fetch_assoc($queryid);
        }
        
        throw new MysqlException($this->error() . "\n" . $this->lastQuery);
    }

    public function dbFreeResult($result)
    {
        return mysqli_free_result($result);
    }

    public function insertId($dblink, $theSequenceName = NULL)
    {
        return mysqli_insert_id($dblink);
    }

    public function numRows($queryid)
    {
        return mysqli_num_rows($queryid);
    }

    public function dbQuery($sql, $dblink)
    {
        $this->lastQuery = $sql;
        return mysqli_query($dblink, $sql);
    }

    public function changeDb($dbname, $dblink)
    {
        return mysqli_select_db($dblink, $dbname);
    }

    public function escape($theString, $dblink)
    {
        return mysqli_real_escape_string($dblink, $theString);
    }

    public function totalRows($table)
    {
        $sql = sprintf('SELECT COUNT(*) AS count FROM `%s`', $table);
        if ($this->query($sql)) {
            $result = $this->fetchAssoc();
            if (isset($result['count'])) {
                return $result['count'];
            }
        }
        
        return '0';
    }

    public function getColumnns($table)
    {
        $sql = sprintf('SHOW COLUMNS FROM `%s`', $table);
        if ($this->query($sql)) {
            $return = array();
            while ($value = $this->fetchAssoc()) {
                $return[] = $value;
            }
            return $return;
        }
    }

    /**
     * Checks to see whether or not the MySQL server supports transactions.
     *
     * @param
     *            dblink, the link (if any) to the database, unused in this implementation.
     * @return bool
     * @access public
     */
    public function serverHasTransaction($dblink)
    {
        $this->queryConstant('SHOW VARIABLES');
        
        if ($this->resultExist()) {
            while ($xxx = $this->fetchRow()) {
                if ($xxx['Variable_name'] == 'have_bdb' && $xxx['Value'] == 'YES') {
                    return true;
                } else 
                    if ($xxx['Variable_name'] == 'have_gemini' && $xxx['Value'] == 'YES') {
                        return true;
                    } else 
                        if ($xxx['Variable_name'] == 'have_innodb' && $xxx['Value'] == 'YES') {
                            return true;
                        }
            }
        }
        
        return false;
    }
}