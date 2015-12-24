<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/sftp_storage.php
 */

/**
 * SFTP Storage Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    'backup_pro_sftp_storage_driver_name' => 'SFTP Storage',
    'backup_pro_sftp_storage_driver_description' => 'This Driver will store backups on a remote SFTP/SSH server.',
    'sftp_host' => 'SFTP Hostname',
    'sftp_host_instructions' => 'The address or domain to the remote server. Don\'t include any prefix like http:// or ftp://',
    'sftp_username' => 'SFTP Username',
    'sftp_username_instructions' => 'If you don\'t know what this is there\'s a good chance you\'ll have to talk to your host to get SFTP sync up and running. ',
    'sftp_password' => 'SFTP / SSH Password',
    'sftp_password_instructions' => 'The password is encrypted for security before storage.',
    'sftp_port' => 'SFTP / SSH Port',
    'sftp_port_instructions' => 'The default is 22 but if your host uses a differnt port for SFTP update it here.',
    'sftp_timeout' => 'Connection Timeout',
    'sftp_timeout_instructions' => 'The maximum time to use for all network operations. Be aware that this value may require tweaking your php.ini settings for the script timeout.',
    'sftp_store_location' => 'SFTP Store Location',
    'sftp_root_instructions' => 'Where on the remote server do you want to store the files. This directory has to exist before the settings can be saved.',
    'sftp_root' => 'SFTP Store Location',
    'sftp_private_key' => 'Private Key Path',
    'sftp_private_key_instructions' => 'The full system path to where your private key file is stored if you\'d like to use that for authentication instead of username and password. '
);