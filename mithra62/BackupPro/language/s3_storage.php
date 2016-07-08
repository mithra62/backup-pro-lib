<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/s3_storage.php
 */

/**
 * FTP Storage Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    'backup_pro_s3_storage_driver_name' => 'Amazon S3 Storage',
    'backup_pro_s3_storage_driver_description' => 'This Driver will store backups on the Amazon S3 service.',
    's3_region' => 'Region',
    's3_region_instructions' => 'The region you created your bucket in shoul be selected here. Be aware that each region has different rules for bucket names so make sure to use the proper region.',
    'configure_s3' => 'Configure Amazon S3 Sync',
    's3_access_key' => 'Access Key ID',
    's3_access_key_instructions' => 'Your Access Key ID identifies you as the party responsible for your S3 service requests. You can find this by signing into your <a href="http://aws.amazon.com" target="_blank">Amazon Web Services account</a>',
    's3_secret_key' => 'Secret Access Key',
    's3_secret_key_instructions' => 'This key is just a long string of characters (and not a file) that you use to calculate the digital signature that you include in the request. For security, both your Access key and Secret key are encrypted before storage.',
    's3_bucket' => 'Bucket Name',
    's3_bucket_instructions' => 'This is basically the master folder name your files will be stored in. Be aware that the bucket name is dependant on the Region so if you have issues double check those.',
    's3_optional_prefix' => 'Optional Prefix',
    's3_optional_prefix_instructions' => 'If you want to store your files in a sub directory within your bucket just enter that here.',
    's3_reduced_redundancy' => 'Reduced Redundancy',
    's3_reduced_redundancy_instructions' => 'Reduced Redundancy Storage (<a href="http://aws.amazon.com/s3/details/#RRS" target="_blank">RRS</a>) is an Amazon S3 storage option that enables customers to reduce their costs by storing noncritical, reproducible data at lower levels of redundancy than Amazon S3\'s standard storage.',
    's3_connection_details' => 'Amazon S3 Connection Details',
);