<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/Mysqldump.php
 */
namespace mithra62\BackupPro\Backup\Database\Engines;

use \mithra62\BackupPro\Backup\Database\DbAbstract;
use \mithra62\BackupPro\Exceptions\Backup\DatabaseException;
use \mithra62\Traits\MySQL\Mycnf;

/**
 * Backup Pro - Mysqldump Backup Engine
 *
 * Performs a backup using the mysqldump command line tool
 *
 * @package Backup\Database\Engines
 * @author Eric Lamb <eric@mithra62.com>
 */
class Mysqldump extends DbAbstract
{
    use Mycnf;

    /**
     * The name of the Database Backup Engine
     * 
     * @var string
     */
    protected $name = 'Mysqldump';

    /**
     * A human readable shortname for the Database Backup Engine
     * 
     * @var string
     */
    protected $short_name = 'mysqldump';

    /**
     * (non-PHPdoc)
     * 
     * @ignore
     *
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::backup()
     */
    public function backupTable($table, $backup_data = true)
    {
        $path_details = pathinfo($this->getContext()->getOutputName());
        $cnf = $this->createMyCnf($this->getContext()
            ->getBackup()
            ->getDbInfo(), $path_details['dirname']);
        $temp_store = $path_details['dirname'] . DIRECTORY_SEPARATOR . $table . '.txt';
        
        $command = $this->getEngineCmd() . " --defaults-extra-file=\"$cnf\" --skip-comments " . $this->db_info['database'] . " $table > \"$temp_store\"";
        if (! $this->getShell(true)
            ->setCommand($command)
            ->execute()) {
            $this->logDebug($this->getShell()
                ->getError());
            // exit;
        }
        
        if(!file_exists($temp_store)) {
            throw new DatabaseException('Can\'t find  "' . $temp_store . '"!');
        }
        
        // now merge the table output with database output
        $handle = fopen($temp_store, "r");
        while (($buffer = fgets($handle)) !== false) {
            $this->getContext()->writeOut($buffer);
        }
        
        fclose($handle);
        unlink($temp_store);
        
        $this->removeMyCnf($path_details['dirname']);
    }

    /**
     * (non-PHPdoc)
     * 
     * @ignore
     *
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::backup_procedure()
     */
    public function backupProcedure($procedure)
    {}
}