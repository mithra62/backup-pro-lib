<?php 
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/local_storage.php
 */

/**
 * Local Storage Language Array
 * mithra62 language translation array
 * @var array
 */
 
$lang = array(
    'backup_pro_local_storage_driver_name' => 'Local Storage',
    'backup_pro_local_storage_driver_description' => 'This Driver will store backups anywhere on your local webserver.',
    'backup_store_location' => 'Backup Store Location',
    'backup_store_location_instructions' => 'Where do you want to store your backups? Ideally, this wouldn\'t be in your site\'s document root (for security) but if it is it won\'t be included within the file backup. Remember to make this directory writable by your webserver so chmod it to either 0666 or 0777.',
);