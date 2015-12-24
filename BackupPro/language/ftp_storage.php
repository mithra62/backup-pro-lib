<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/ftp_storage.php
 */

/**
 * FTP Storage Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    'backup_pro_ftp_storage_driver_name' => 'FTP Storage',
    'configure_ftp' => 'Configure FTP Sync',
    'ftp_hostname' => 'FTP Hostname',
    'ftp_hostname_instructions' => 'The address or domain to the remote server. Don\'t include any prefix like http:// or ftp://',
    'ftp_username' => 'FTP Username',
    'ftp_username_instructions' => 'If you don\'t know what this is there\'s a good chance you\'ll have to talk to your host to get FTP sync up and running. ',
    'ftp_password' => 'FTP Password',
    'ftp_password_instructions' => 'The password is encrypted for security before storage.',
    'ftp_port' => 'FTP Port',
    'ftp_port_instructions' => 'The default is 21 but if your host uses a differnt port for FTP update it here.',
    'ftp_passive' => 'Passive Mode',
    'ftp_passive_instructions' => 'If checked then all transfers will be done using the PASV method. ',
    'ftp_ssl' => 'Use SSL',
    'ftp_ssl_instructions' => 'If your FTP server supports SSL you\'re highly encouraged to enable this so communication is secured between your site and FTP server.',
    'ftp_timeout' => 'Connection Timeout',
    'ftp_timeout_instructions' => 'The maximum time to use for all network operations. Be aware that this value may require tweaking your php.ini settings for the script timeout.',
    'ftp_store_location' => 'FTP Store Location',
    'ftp_store_location_instructions' => 'Where on the remote server do you want to store the files. This directory has to exist before the settings can be saved.',
    'ftp_directory_missing' => 'The FTP remote directory doesn\'t exist.',
    'backup_pro_ftp_storage_driver_description' => 'This Driver will store backups on a remote FTP server.'
)
;