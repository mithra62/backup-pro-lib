<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/language/settings_db.php
 */

/**
 * Language Array
 * mithra62 language translation array
 * 
 * @var array
 */
$lang = array(
    
    // db settings
    'config_extra_archive_sql' => 'Configure Archived SQL Dump (Advanced Users Only!)',
    'db_backup_archive_pre_sql' => 'Archive Additional SQL (top)',
    'db_backup_archive_pre_sql_instructions' => 'If configured, the included SQL statement(s) will be included in the database archive before anything else is added. It should be a single SQL query per line, and use proper syntax and escaping. Your SQL will NOT be modified in any way by Backup Pro. Use at your own risk.',
    'db_backup_archive_post_sql' => 'Archive Additional SQL (bottom)',
    'db_backup_archive_post_sql_instructions' => 'If configured, the included SQL statement(s) will be added into the database archive as the very last SQL statements in the archive. It should be a single SQL query per line, and use proper syntax and escaping. Your SQL will NOT be modified in any way by Backup Pro. Use at your own risk.',
    'config_execute_sql' => 'Configure Additional SQL Commands (Advanced Users Only!)',
    'db_backup_execute_pre_sql' => 'Execute Additional SQL (start)',
    'db_backup_execute_pre_sql_instructions' => 'If configured, the included SQL statement will be executed against the database, using an arbitrary connection, before any backup centric SQL command is called. Your SQL will NOT be modified in any way by Backup Pro. Use at your own risk.',
    'db_backup_execute_post_sql' => 'Execute Additional SQL (end)',
    'db_backup_execute_post_sql_instructions' => 'If configured, the included SQL statement will be executed against the database, using an arbitrary connection, after the backup file has been completely written to the backup archive. Your SQL will NOT be modified in any way by Backup Pro. Use at your own risk.',
    'config_ignore_sql' => 'Configure Exclude Data',
    'db_backup_ignore_tables' => 'Exclude Tables',
    'db_backup_ignore_tables_instructions' => 'Which, if any, tables would you like excluded from the database backups? Any selected tables will be ignored and not archived.',
    'db_backup_ignore_table_data' => 'Exclude Data',
    'db_backup_ignore_table_data_instructions' => 'Any selected tables will have only their schema archived; any associated data will be ignored. ',
    'db_backup_alert_threshold' => 'Database Backup Alert Frequency',
    'db_backup_alert_threshold_instructions' => 'How many days are backups supposed to be ran? If a bacukp hasn\'t been ran in as many days as set here a notification will be sent alerting the system administrators. Enter 0 to disable.',
    
    'db_backup_past_expectation' => 'A database backup hasn\'t happened in %1$s! You should take a <a href="%2$s">database backup</a> ASAP to ensure system stability. ',
    'file_backup_past_expectation' => 'A file backup hasn\'t happened in %1$s! You should take a <a href="%2$s">file backup</a> ASAP to ensure system stability. ',
    'db_backup_past_expectation_stub' => '',
    'file_backup_past_expectation_stub' => '',
    'php_backup_method_select_chunk_limit' => 'SELECT Chunk Limit',
    'php_backup_method_select_chunk_limit_instructions' => 'To handle memory needs Backup Pro "paginates" through your data with the (PHP Backup Engine only). Simply, increasing setting this will up your memory needs while decreasing how long your backups take. Decreasing will lower memory needs but increase how long backups take. ',
    
    'mysqldump_command' => 'Mysqldump Command',
    'mysqldump_command_instructions' => 'Depending on your system needs you may need to customize the command used to execute mysqldump. Be sure NOT to include any connection or database details; Backup Pro will inject that automagically for you.'
)
;