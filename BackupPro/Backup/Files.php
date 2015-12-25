<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Files.php
 */
namespace mithra62\BackupPro\Backup;

use \mithra62\BackupPro\Exceptions\Backup\FilesException;

/**
 * Backup Pro - Files Backup Object
 *
 * Contains the logic for executing a files backup
 *
 * @package Backup\Files
 * @author Eric Lamb <eric@mithra62.com>
 */
class Files extends AbstractBackup
{

    /**
     * The paths on the filesystem we want to exclude from the backup
     * 
     * @var array
     */
    protected $exclude_paths = array();

    /**
     * Flag to determine whether regular expressions are used to exclude files
     * 
     * @var bool
     */
    protected $exclude_regex = true;

    /**
     * The paths we want to include in our backup
     * 
     * @var array
     */
    protected $backup_paths = array();

    /**
     * The number of files were processing
     * 
     * @var number
     */
    protected $total_files = 0;

    /**
     * The uncompressed file size for the backup
     * 
     * @var number
     */
    protected $total_file_size = 0;

    /**
     * The Files object
     * 
     * @var \mithra62\Files
     */
    protected $file = null;

    /**
     * The total filesize of all the combined backed up files
     * 
     * @var number
     */
    protected $total_uncompressed_filesize = 0;

    /**
     * Sets the File object for use
     * 
     * @param \mithra62\Files $file            
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setFile(\mithra62\Files $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Sets the total number of files
     * 
     * @param number $total            
     * @param bool $refresh            
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setTotalFiles($total, $refresh = false)
    {
        if ($refresh) {
            $this->total_files = $total;
        } else {
            $this->total_files = $this->total_files + $total;
        }
        
        return $this;
    }

    /**
     * Returns the total number of files being processed
     * 
     * @return number
     */
    public function getTotalFiles()
    {
        return $this->total_files;
    }

    /**
     * Sets the total uncompressed file size total
     * 
     * @param number $size
     *            The number to increment with
     * @param string $refresh
     *            Whether to reset the total to the passed $sizesa
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setTotalFileSize($size = 0, $refresh = false)
    {
        if ($refresh) {
            $this->total_uncompressed_filesize = $size;
        } else {
            $this->total_uncompressed_filesize += $size;
        }
        
        return $this;
    }

    /**
     * Returns the total uncompressed filesize the backup takes up
     * 
     * @return number
     */
    public function getTotalFileSize()
    {
        return $this->total_uncompressed_filesize;
    }

    /**
     * Sets the file exclude paths
     * 
     * @param array $paths            
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setExcludePaths(array $paths)
    {
        $this->exclude_paths = $paths;
        return $this;
    }

    /**
     * Returns the file exclude paths
     * 
     * @return array
     */
    public function getExcludePaths()
    {
        $this->exclude_paths[] = $this->backup->getStorage()->getFileBackupDir();
        return $this->exclude_paths;
    }

    /**
     * Sets the paths to include in our backup
     * 
     * @param array $paths            
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setBackupPaths(array $paths)
    {
        $this->backup_paths = $paths;
        return $this;
    }

    /**
     * Returns the backup paths
     * 
     * @return array
     */
    public function getBackupPaths()
    {
        return $this->backup_paths;
    }

    /**
     * Set whether regular expressions are used for file exclusion
     * 
     * @param bool $exclude_regex            
     * @return \mithra62\BackupPro\Backup\Files
     */
    public function setExludeRegex($exclude_regex)
    {
        $this->exclude_regex = $exclude_regex;
        return $this;
    }

    /**
     * Returns whether we should use regular expressions on file exclusion
     * 
     * @return bool
     */
    public function getExludeRegex()
    {
        return $this->exclude_regex;
    }

    /**
     * Performs the file backup
     * 
     * @param string $file_name
     *            The name of the containing folder the backup should be saved as
     * @param \mithra62\Compress $compress
     *            The compression object
     * @return string The path to the newly created archive file
     */
    public function backup($file_name, \mithra62\Compress $compress)
    {
        $path = $this->backup->startTimer()
            ->getStorage()
            ->getFileBackupNamePath($file_name);
        $backup_paths = $this->getBackupPaths();
        $progress = $this->backup->getProgress();
        $progress->writeLog('backup_progress_bar_start_file_list', 'na', 1);
        $backup_files = array();
        foreach ($backup_paths as $dir) {
            $dir = trim($dir);
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::LEAVES_ONLY);
            
            $rel_dir = basename($dir);
            foreach ($files as $file) {
                if (! $file->isDir() && $file->isReadable()) {
                    $this->setTotalFiles(1);
                    $filePath = $file->getRealPath();
                    $should_exclude = false;
                    foreach ($this->getExcludePaths() as $exclude) {
                        $exclude = trim($exclude);
                        if (empty($exclude)) {
                            continue;
                        }
                        
                        $length = strlen($exclude);
                        if (substr(trim($filePath), 0, $length) == $exclude) {
                            $should_exclude = true;
                        } else 
                            if ($this->getExludeRegex() && $this->getRegex()->match(trim($exclude), trim($filePath))) {
                                $should_exclude = true;
                            }
                    }
                    
                    if (! $should_exclude) {
                        $relative = str_replace($dir, '', $filePath);
                        $this->setTotalFileSize($file->getSize());
                        $backup_files[] = array(
                            'path' => $filePath,
                            'rel' => rtrim($rel_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($relative, DIRECTORY_SEPARATOR)
                        );
                    }
                }
            }
        }
        
        if (count($backup_files) == 0) {
            throw new FilesException("Nothing to backup. No files. Nothing... Lame... Check your Exclude Paths since they matched all files...");
        }
        
        $progress->writeLog('backup_progress_bar_stop_file_list', count($backup_files), 2);
        $compress->create($path);
        
        foreach ($backup_files as $file) {
            $compress->add($file['path'], $file['rel']);
        }
        
        $path = $compress->close();
        
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
    public function writeDetails(\mithra62\BackupPro\Backup\Details $details, $file_path, $backup_method = 'files')
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
            'uncompressed_size' => $this->getTotalFileSize(),
            'item_count' => $this->getTotalFiles(),
            'backup_type' => 'files'
        );
        
        $details->addDetails($file_name, $details_path, $base_details);
        
        return $this;
    }
}