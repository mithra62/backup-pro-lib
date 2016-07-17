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
    public function writeLog($msg, $item = false, $total_items = 0, $item_number = 0)
    {
        try {
            if( $this->shouldLog() ) {
                if ($item_number > $total_items) {
                    $item_number = $total_items;
                }
                
                $log = array(
                    'total_items' => $total_items,
                    'item_number' => $item_number,
                    'msg' => $msg,
                    'item' => $item
                );
                
                $log = json_encode($log);
                $this->getFile()->write($this->getProgressLogFile(), $log, 'w+');
            }
        }
        catch (ProgressException $e) {
            $e->logException($e);
            throw new ProgressException($e->getMessage());
        } catch (\Exception $e) {
            $e->logException($e);
            throw new \Exception($e->getMessage());
        }
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
     * @return \JaegerApp\\Files
     */
    public function getFile()
    {
        if (is_null($this->file)) {
            $this->file = new m62Files();
        }
        
        return $this->file;
    }
    
    /**
     * Determine's if progress should be logged
     * @return bool
     */
    protected function shouldLog()
    {
        $this->stopTimer();
        if( $this->computeElapsedTime() >= 1) {
            $this->computeElapsedTime();
            $this->startTimer();
            return true;
        }
    }
    
    /**
     * Calculates the execution time for the progress writing
     * @return int
     */
    protected function computeElapsedTime() {
        return $this->timer_stop - $this->timer_start;
    }
    
    /**
     * Returns the current time
     * @return int
     */
    protected function getTime() {
        $mtime = microtime();
        $mtime = explode( " ", $mtime );
        return $mtime[1] + $mtime[0];
    }    

    /**
     * Starts the timer
     * @return \mithra62\BackupPro\Backup\Progress
     */
    protected function startTimer() {
        $this->timer_start = $this->getTime();
        return $this;
    }
    
    /**
     * Stops the timer
     * @return \mithra62\BackupPro\Backup\Progress
     */
    protected function stopTimer() {
        $this->timer_stop = $this->getTime();
        $this->timer_elapsed = $this->computeElapsedTime();
        return $this;
    }
    
    /**
     * Resets the timer back to defaults
     * @return \mithra62\BackupPro\Backup\Progress
     */
    protected function resetTimer() {
        $this->timer_start = 0;
        $this->timer_stop = 0;
        $this->timer_elapsed = 0;
        return $this;
    }    
}