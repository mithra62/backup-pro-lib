<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Concrete5.php
 */
namespace mithra62\BackupPro\Platforms\View;

use JaegerApp\Platforms\View\Concrete5 as C5View;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

/**
 * Backup Pro - Concrete5 View abstraction
 *
 * Contains the Concrete5 specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Concrete5 extends C5View
{
    use ViewHelpers;
}