<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Restore/Database/Engines/Mysql.php
 */

namespace mithra62\BackupPro\Restore\Database\Engines;

use \mithra62\BackupPro\Restore\Database\DbAbstract;
use \mithra62\Traits\MySQL\Mycnf;

/**
 * Backup Pro - MySQL Database Restore Engine
 *
 * Restores a database using the MySQL command line tool
 *
 * @package 	Restore\Engine
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Mysql extends DbAbstract
{   
    use Mycnf;
    
    /**
     * The name of the Database Restore Engine
     * @var string
     */
    protected $name = 'mysql';
    
    /**
     * A human readable shortname for the Database Restore Engine
     * @var string
     */
    protected $short_name = 'mysql';
    
    /**
     * Restores a database using the MySQL command line tool
     * @param string $database The database we want to restore
     * @param string $restore_file The full path to the SQL file we want to use
     * @return boolean
     */
    public function restore($database, $restore_file)
    {
        $path_info = pathinfo($restore_file);
        $db_info = $this->getContext()->getRestore()->getDbInfo();
        $cnf = $this->createMyCnf($db_info, $path_info['dirname']);
    	
        $command = $this->getEngineCmd()." --defaults-extra-file=\"$cnf\" ".$db_info['database']." < $restore_file";
        if( !$this->getShell(true)->setCommand($command)->execute() )
        {
            $this->logDebug($this->getShell()->getError());
            //exit;
        }
        
        $this->removeMyCnf($path_info['dirname']);
    }
}