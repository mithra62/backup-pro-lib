<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Details.php
 */
 
namespace mithra62\BackupPro\Backup;

use mithra62\BackupPro\Exceptions\Backup\DetailsException;

/**
 * Backup Pro - Backup Meta Details Object
 *
 * Handles writing out the backup meta files
 *
 * @package 	Backup
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Details
{
    /**
     * The name of the directory the details are all stored in
     * @var string
     */
    private $details_directory = '.meta';
    
    /**
     * The file extension the backup details will contain is
     * @var string
     */
    private $details_ext = '.m62';
    
    /**
     * The outline for how data should be stored
     * @var array
     */
    private $details_prototype = array(
        'note' => '',
        'hash' => '',
        'locked' => 0,
        'storage' => array(),
        'details' => array(),
        'item_count' => 0,
        'uncompressed_size' => 0,
        'compressed_size' => 0,
        'created_by' => 0,
        'created_date' => 0,
        'backup_type' => 'database',
        'database_backup_type' => '',
        'verified' => 0,
        'time_taken' => 0,
        'max_memory' => 0,
        'file_name' => '',
        'details_file_name' => '',
        'can_download' => 0,
        'can_restore' => 0,
        'verified_details' => array(),
    );
    
    /**
     * Returns the path to where the details file should be stored
     * @param string $path
     */
    public function getDetailsPath( $path )
    {
        return rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->getDetailsDir();
    }
    
    /**
     * Returns the base prototype the details files will contain
     * @return \mithra62\BackupPro\Backup\array
     */
    public function getDetailsPrototype()
    {
        return $this->details_prototype;
    }
    
    /**
     * Sets the base prototype the details files will contain
     * @param array $prototype
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function setDetailsPrototype(array $prototype)
    {
        $this->details_prototype = $prototype;
        return $this;
    }
    
    /**
     * Sets the file extension for the backup details file
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getDetailsExt()
    {
        return $this->details_ext;
    }
    
    /**
     * Returns the file extension for the backup details file
     * @param string $ext
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function setDetailsExt($ext)
    {
        $this->details_ext = $ext;
        return $this;
    }
    
    /**
     * Returns the name of the Details Storage directory
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getDetailsDir()
    {
        return $this->details_directory;
    }
    
    /**
     * Sets the name of the Details Storage directory
     * @param string $dir
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function setDetailsDir($dir)
    {
        $this->details_directory = $dir;
        return $this;
    }
    
    /**
     * Returns the details of a given backup from the .meta directory
     * @param string $file_name
     * @param string $path
     * @return array
    */
    public function getDetails($file_name, $path = null)
    {      
        if( file_exists($file_name) )
        {
            $details_file = $file_name;
        }
        else
        {
            $details_file = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->getDetailsDir().DIRECTORY_SEPARATOR. $file_name.$this->getDetailsExt();         
            if( !file_exists($details_file) )
            {
                $this->createDetailsFile($file_name, $path);
            }
        }

        $data = file_get_contents($details_file);
        if( !$data )
        {
            $data = $this->createDetailsFile($file_name, $path);
        }
        
        $return = json_decode($data, true);
        return $return;
    }
    
    /**
     * Creates the meta details file for the given backup
     * @param string $file_name
     * @param string $path
     * @param array $data
     */
    public function createDetailsFile($file_name, $path, array $data = array())
    {
        $file_path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR. $file_name;
        if( !file_exists($file_path) )
        {
            throw new DetailsException('File doesn\'t exist! '. $file_path);
        }
        
        $details_file_name = $file_name.$this->getDetailsExt();
        $save_path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->getDetailsDir().DIRECTORY_SEPARATOR.$details_file_name;
        $data = array_merge($data, $this->getDetailsPrototype());
        $data['hash'] = md5_file($file_path);
        $data['compressed_size'] = filesize($file_path);
        $data['file_name'] = $file_name;
        $data['details_file_name'] = $details_file_name;
        $data = json_encode($data);
        file_put_contents($save_path, $data );
        return $data;
    }
    
    /**
     * Writes the updated details info for a backup
     * @param string $file_name
     * @param string $path
     * @param array $data
     */
    public function addDetails($file_name, $path, array $data)
    {
        $details = $this->getDetails($file_name, $path);
        $save_path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->getDetailsDir().DIRECTORY_SEPARATOR.$file_name.$this->getDetailsExt();
        $data = array_merge($details, $data);
        $data = json_encode($data);
        file_put_contents($save_path, $data);
    }
    
    /**
     * Removes the meta file related to a backup
     * @param string $file_name
     * @param string $path
     * @return boolean
     */
    public function removeDetailsFile($file_name, $path)
    {
        $remove_path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->getDetailsDir().DIRECTORY_SEPARATOR.$file_name.$this->getDetailsExt();
        if(file_exists($remove_path))
        {
            return unlink($remove_path);
        }
    }
}