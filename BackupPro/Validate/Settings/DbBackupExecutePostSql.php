<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbBackupExecutePostSql.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Db Backup Execute Post Sql Validation Object
 *
 * Validates the Db Backup Execute Post Sql setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbBackupExecutePostSql extends DbBackupArchivePreSql
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_backup_execute_post_sql';
}