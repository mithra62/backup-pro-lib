<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Smarty.php
 */
 
namespace mithra62\BackupPro\Platforms\View;

use JaegerApp\Platforms\View\Smarty AS Ext;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

/**
 * Backup Pro - Smarty View abstraction
 *
 * Contains the Smarty specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Smarty extends Ext
{
    use ViewHelpers;
}