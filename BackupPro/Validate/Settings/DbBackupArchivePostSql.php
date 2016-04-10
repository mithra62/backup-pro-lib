<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbBackupArchivePostSql.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Db Backup Archive Post Sql Validation Object
 *
 * Validates the Db Backup Archive Post Sql setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbBackupArchivePostSql extends DbBackupArchivePreSql
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_backup_archive_post_sql';
}