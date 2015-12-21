<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Console.php
 */
 
namespace mithra62\BackupPro;

use mithra62\Console AS m62Console;

/**
 * Backup Pro - Console Object
 *
 * Delegates the Console operations
 *
 * @package 	BackupPro\Console
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Console extends m62Console
{
    public function getArgs($strict = false, $force = false)
    {
        $args = parent::getArgs($strict, $force);
        $args->addOption(array('backup', 'B'), array(
            'default'     => 'database',
            'description' => '[file] [database] [integrity]'));
        $args->parse();
        return $args;
    }
}