<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/PhpBackupMethodSelectChunkLimit.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Php Backup Method Select Chunk Limit Validation Object
 *
 * Validates the Php Backup Method Select Chunk Limit setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class PhpBackupMethodSelectChunkLimit extends MaxDbBackups
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'php_backup_method_select_chunk_limit';
}