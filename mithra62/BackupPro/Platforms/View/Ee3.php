<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Ee3.php
 */
namespace mithra62\BackupPro\Platforms\View;

use JaegerApp\Platforms\View\Ee3 as Ee3View;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

/**
 * Backup Pro - ExpressionEngine 3 View abstraction
 *
 * Contains the ExpressionEngine 3 specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Ee3 extends Ee3View
{
    use ViewHelpers;
}