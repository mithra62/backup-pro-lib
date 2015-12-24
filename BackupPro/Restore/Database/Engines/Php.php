<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Restore/Database/Engines/Php.php
 */
namespace mithra62\BackupPro\Restore\Database\Engines;

use \mithra62\BackupPro\Restore\Database\DbAbstract;

/**
 * Backup Pro - PHP Restore Engine
 *
 * Restores a database using a pure PHP solution
 *
 * @package Restore\Engine
 * @author Eric Lamb <eric@mithra62.com>
 */
class Php extends DbAbstract
{

    /**
     * The name of the Database Restore Engine
     * 
     * @var string
     */
    protected $name = 'php';

    /**
     * A human readable shortname for the Database Restore Engine
     * 
     * @var string
     */
    protected $short_name = 'php';

    /**
     * The backup types this restore engine can work with
     * 
     * @var array
     */
    protected $allowed_backup_types = array(
        'php'
    );

    /**
     * Restores a database using the pure PHP solution
     * 
     * @param string $database
     *            The database we want to restore
     * @param string $restore_file
     *            The full path to the SQL file we want to use
     * @return boolean
     */
    public function restore($database, $restore_file)
    {
        $db = $this->getContext()
            ->getRestore()
            ->getDb()
            ->setDbName($database);
        $file = fopen($restore_file, "r");
        
        if ($file === FALSE) {
            die(sprintf("Can't open %s", $this->m_output));
        }
        
        while (! feof($file)) {
            $theQuery = fgets($file);
            $theQuery = substr($theQuery, 0, strlen($theQuery) - 1);
            
            if (trim($theQuery) != '') {
                $db->query($theQuery);
            }
        }
        
        return fclose($file);
    }
}