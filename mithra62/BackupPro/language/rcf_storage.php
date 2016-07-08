<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/rcf_storage.php
 */

/**
 * Rackspace Cloud Files Storage Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    'backup_pro_rcf_storage_driver_name' => 'Rackspace Cloud Files',
    'backup_pro_rcf_storage_driver_description' => 'This Driver will store backups on the Rackspace Cloud Files.',

    'rcf_optional_prefix' => 'Optional Prefix Directory',
    'rcf_optional_prefix_instructions' => 'If you want to use a container\'s subfolder to store backups specify the path to use here.',
    'configure_rcf' => 'Configure Rackspace Cloud Files',
    'rcf_username' => 'Rackspace Username',
    'rcf_username_instructions' => 'Use your Rackspace Cloud username as the username for the API. For security, both your Access key and Secret key are encrypted before storage.',
    'rcf_api' => 'API Access key',
    'rcf_api_instructions' => 'Obtain your API access key from the Rackspace Cloud Control Panel in the <a href="https://manage.rackspacecloud.com/APIAccess.do" target="_blank">Your Account</a>. For security, both your Access key and Secret key are encrypted before storage.',
    'rcf_container' => 'Container Name',
    'rcf_container_instructions' => 'This is basically the master folder name your files will be stored in. If it doesn\'t exist it\'ll  be created. If you don\'t enter a bucket name one will be created for you.',
    'rcf_connect_fail' => 'The Rackspace Cloud Files credentials aren\'t correct.',
    'rcf_location' => 'Account Location',
    'rcf_location_instructions' => 'You can determine the location to use based on the Rackspace retail site which was used to create your account. <a href="http://www.rackspacecloud.com">US</a> or <a href="http://www.rackspace.co.uk">UK</a>.',
    'rcf_connection_details' => 'Rackspace Cloud Files Connection Details'
);