<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/gcs_storage.php
 */

/**
 * FTP Storage Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    'backup_pro_gcs_storage_driver_name' => 'Google Cloud Storage',
    'backup_pro_gcs_storage_driver_description' => 'This Driver will store backups on the Google Cloud Storage service.',
    'configure_gcs' => 'Configure Google Cloud Storage',
    'gcs_access_key' => 'Access Key ID',
    'gcs_access_key_instructions' => 'Your Access Key ID identifies you as the party responsible for your Google Cloud Storage service requests. You can find this by signing into your <a href="http://aws.amazon.com" target="_blank">Amazon Web Services account</a>',
    'gcs_secret_key' => 'Secret Access Key',
    'gcs_secret_key_instructions' => 'This key is just a long string of characters (and not a file) that you use to calculate the digital signature that you include in the request. For security, both your Access key and Secret key are encrypted before storage.',
    'gcs_bucket' => 'Bucket Name',
    'gcs_bucket_instructions' => 'This is basically the master folder name your backups will be stored in. If it doesn\'t exist it\'ll  be created. If you don\'t enter a bucket name one will be created for you.',
    'gcs_prune_remote' => 'Prune Google Cloud Storage Backups',
    'gcs_prune_remote_instructions' => 'Should Backup Pro include the remote files in the Auto Prune and Maximum Backup limits?.',
    'gcs_optional_prefix' => 'Optional Prefix',
    'gcs_optional_prefix_instructions' => 'If you want to store your files in a sub directory within your bucket just enter that here.',
    'gcs_reduced_redundancy' => 'Reduced Redundancy',
    'gcs_reduced_redundancy_instructions' => 'Reduced Redundancy Storage (<a href="http://aws.amazon.com/s3/details/#RRS" target="_blank">RRS</a>) is an Amazon S3 storage option that enables customers to reduce their costs by storing noncritical, reproducible data at lower levels of redundancy than Amazon S3\'s standard storage.',
    'gcs_connection_details' => 'Google Cloud Storage Connection Details',
    
);