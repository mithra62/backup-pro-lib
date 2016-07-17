<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Progress.php
 */
namespace mithra62\BackupPro\Backup;

use JaegerApp\Files as m62Files;
use mithra62\BackupPro\Exceptions\Backup\ProgressException;

/**
 * Backup Pro - Backup Progress Object
 *
 * Contains the methods for setting the progress of a backup
 *
 * @package Backup
 * @author Eric Lamb <eric@mithra62.com>
 */
class Progress
{   
    /**
     * The File object
     * 
     * @var mithra62\File
     */
    protected $file = null;

    /**
     * The path to the progress log file
     * 
     * @var string
     */
    protected $progress_log_path = null;

    /**
     * The name of the progress log file
     * 
     * @var string
     */
    protected $progress_log_name = 'progress.data';

    /**
     * The full path to the progress log file
     * 
     * @var string
     */
    protected $progress_log_file = null;
    
    /**
     * Timer start time
     * @var int
     */
    protected $timer_start = 0;
    
    /**
     * Timer end time
     * @var int
     */
    protected $timer_stop = 0;
    
    /**
     * The complete elapsed time
     * @var int
     */
    protected $timer_elapsed = 0;
    
    /**
     * How often should we write to the progress log in seconds
     * @var int
     */
    protected $log_interval = 1;

    /**
     * set it up
     * 
     * @see mithra62\setProgressLogFile()
     * @param string $log_path
     *            optional
     */
    public function __construct($log_path = null)
    {
        if (! is_null($log_path)) {
            $this->setProgressLogFile($log_path);
        }
        
        $this->startTimer();
    }

    /**
     * Sets the path to where the progress log file will reside
     * 
     * @param string $path            
     * @return \mithra62\BackupPro\Backup\Progress
     */
    public function setProgressLogFile($path)
    {
        $this->progress_log_path = rtrim($path, '/');
        $this->progress_log_file = $path . '/' . $this->progress_log_name;
        return $this;
    }

    /**
     * Returns the full path to the progress log file
     * 
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getProgressLogFile()
    {
        return $this->progress_log_file;
    }

    /**
     * Writes out the progress log for the progress bar status updates
     * 
     * @param string $msg            
     * @param int $total_items            
     * @param int $item_number            
     */
    public function writeLog($msg, $total_items = 0, $item_number = 0)
    {
        return;
        if ($item_number > $total_items) {
            $item_number = $total_items;
        }
        
        $log = array(
            'total_items' => $total_items,
            'item_number' => $item_number,
            'msg' => $msg
        );
        $log = json_encode($log);
        
        $this->getFile()->write($this->getProgressLogFile(), $log, 'a+');
    }

    /**
     * Removes the progress log
     */
    public function removeLog()
    {
        $this->getFile()->delete($this->getProgressLogFile());
    }

    /**
     * Creates an instance of the File object
     * 
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function getFile()
    {
        if (is_null($this->file)) {
            $this->file = new m62Files();
        }
        
        return $this->file;
    }
    
    protected function computeElapsedTime() {
        return $this->timer_stop - $this->timer_start;
    }
    
    protected function getElapsedTime() {
        $mtime = microtime();
        $mtime = explode( " ", $mtime );
        return $mtime[1] + $mtime[0];
    }    

    protected function startTimer() {
        $this->timer_start = $this->getElapsedTime();
    }
    
    protected function stopTimer() {
        $this->timer_stop = $this->getElapsedTime();
        $this->timer_elapsed = $this->computeElapsedTime();
    }
    
    protected function resetTimer() {
        $this->timer_start   = 0;
        $this->timer_stop    = 0;
        $this->timer_elapsed = 0;
    }    
}