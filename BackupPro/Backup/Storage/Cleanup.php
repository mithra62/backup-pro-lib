<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Cleanup.php
 */
 
namespace mithra62\BackupPro\Backup\Storage;

use mithra62\BackupPro\Backup\Details;

/**
 * Backup Pro - Backup Storage Cleanup Object
 *
 * Contains the methods for triming and removing stored backups
 *
 * @package 	Backup
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Cleanup
{
    /**
     * An instance of the calling object
     * @var \mithra62\BackupPro\Backup\Storage
     */
    private $context = null;
    
    /**
     * An instance of the Backup Details object
     * @var \mithra62\BackupPro\Backup\Details
     */
    private $details = null;    
    
    /**
     * The setup locations where backup meta details are stored
     * @var array
     */
    private $storage_details = array();
    
    /**
     * The exiting backups on the system
     * @var array
     */
    private $backups = array();
    
    /**
     * Removes the backups on the system so the total == $total for backup $type
     * @param string $total
     * @param string $type
     * @return void|boolean
     * @return \mithra62\BackupPro\Backup\Cleanup
     */    
    public function counts($total = '0', $type = 'database')
    {
        $total = (int)$total;
        if($total == '0')
        {
            return $this;
        }
        
        //get the backups and clean things up for processing
        $backups = $this->filterBackups($this->getBackups(), $type);
        if(count($backups) < $total)
        {
            return $this;
        }
        
        //check if we need to remove any for
        $count = (count($backups)-$total);
        $i = 1;
        ksort($backups);
        foreach($backups AS $backup)
        {
            if($count >= $i)
            {
                $this->getContext()->remove($backup, $this->getDetails(), true);
            }
            else
            {
                break;
            }
            $i++;
        }
        
        return $this;
    }
    
    /**
     * Filters the backups into a sortable by date key array by group
     * @param array $backups
     * @param string $type
     * @return array
     */
    public function filterBackups(array $backups, $type = 'database')
    {
        $arr = array();
        if(count($backups[$type]) >= '1')
        {
            foreach($backups[$type] AS $backup)
            {
                $arr[$backup['created_date']] = $backup;
            }
        } 
        
        return $arr;
    }
    
    /**
     * Removes the oldest backups to keep the space under $max_size
     * @param int $max_size The maximum space we're alloting for backup usage
     * @return \mithra62\BackupPro\Backup\Cleanup
     */  
    public function autoThreshold($max_size)
    {
        if($max_size == '0')
        {
            return $this;
        }
        
        $arr = array();
        $backups = $this->getBackups();
        if(count($backups) >= '1')
        {
            foreach($backups AS $type => $items)
            {
                foreach($items AS $backup)
                {
                    $arr[$backup['created_date']] = $backup;
                }
            }
        }
        
        ksort($arr);
        while($this->getContext()->getSpaceUsed($arr) > $max_size)
        {
            $backup = array_shift($arr);
            print_R($backup);
            exit;
            if($backup != '')
            {
                $this->getContext()->remove($backup, $this->getDetails(), true);
            }
        }
        
        return $this;
    }  
    
    /**
     * Removes duplicate backups from the system
     * @param number $allow_duplicates
     * @return \mithra62\BackupPro\Backup\Cleanup
     */
    public function duplicates($allow_duplicates = '0')
    {
        if( $allow_duplicates == '1' )
        {
            return $this;
        }
        
        $backups = $this->getBackups();
        
        
        foreach( $backups AS $type => $_backups )
        {
            if( count($_backups) <= '1' )
            {
                continue; //we want to ensure at least 1 (or none) backups for this check so we don't remove the ONLY backup
            }
            
            $hashes = array();
            $duplicates = array();
            foreach($_backups AS $time => $backup)
            {
                if( isset($hashes[$backup['hash']]) )
                {
                    $duplicates[] = $backup['file_name'];
                }
                else
                {
                    $hashes[$backup['hash']] = $backup['hash'];
                }
            }
            
            foreach($duplicates AS $hashed_time => $check)
            {
                foreach($_backups AS $backup_time => $backup)
                {
                    if( $check == $backup['file_name'] )
                    {
                        $this->getContext()->remove($backup, $this->getDetails(), true); //duplicate hash so remove it
                        //unset($hashes[$hashed_time]);
                    }                
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Sets an instance of the calling object
     * @param object $context
     * @return \mithra62\BackupPro\Backup\Cleanup
     */
    public function setContext(\mithra62\BackupPro\Backup\Storage $context)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Storage
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Sets the locations where backup meta is stored
     * @param array $details
     * @return \mithra62\BackupPro\Backup\Storage\Cleanup
     */
    public function setStorageDetails(array $details)
    {
        $this->storage_details = $details;
        return $this;
    }
    
    /**
     * Returns the locations where backup meta is stored
     * @return array
     */
    public function getStorageDetails()
    {
        return $this->storage_details;
    }
    
    /**
     * Sets the backups for use in Cleanup
     * @param array $backups
     * @return \mithra62\BackupPro\Backup\Storage\Cleanup
     */
    public function setBackups(array $backups)
    {
        $this->backups = $backups;
        return $this;
    }
    
    /**
     * Returns the backups to use for Cleanup
     * @return array
     */
    public function getBackups()
    {
        return $this->backups;
    }
    
    /**
     * Sets the Backup Details object
     * @param Details $details
     * @return \mithra62\BackupPro\Backup\Storage\Cleanup
     */
    public function setDetails(Details $details)
    {
        $this->details = $details;
        return $this;
    }
    
    /**
     * Returns the Details object
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function getDetails()
    {
        return $this->details;
    }
}