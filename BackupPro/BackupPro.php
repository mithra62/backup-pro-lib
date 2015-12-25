<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/BackupPro.php
 */
namespace mithra62\BackupPro;

/**
 * Backup Pro - Details Object
 *
 * Contains the details for the add-on
 *
 * @package BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
interface BackupPro
{

    /**
     * The version of the add-on
     * 
     * @var float
     */
    const version = '3.2.1';

    /**
     * The Build version
     * 
     * @var string
     */
    const build = '111020151849';

    /**
     * The language key of the add-on name
     * 
     * @see mithra62\Language
     * @var string
     */
    const name = 'backup_pro';

    /**
     * The name of the language file inside the appropriate directory
     * 
     * @var string
     */
    const lang_file = 'backup_pro';

    /**
     * The language key for the add-on description
     * 
     * @var string
     */
    const desc = 'backup_pro_desc';

    /**
     * The URL to the add-on docs
     * 
     * @var string
     */
    const docs_url = 'https://mithra62.com/docs/table-of-contents/backup-pro';

    /**
     * The URL to the base site for docs and details
     * 
     * @var string
     */
    const base_url = 'https://mithra62.com/';
}