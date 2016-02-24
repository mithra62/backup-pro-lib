<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Eecms.php
 */
namespace mithra62\BackupPro\Platforms\View;

use mithra62\Platforms\View\Eecms as EecmsView;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

/**
 * Backup Pro - ExpressionEngine 2 View abstraction
 *
 * Contains the ExpressionEngine 2 specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Eecms extends EecmsView
{
    use ViewHelpers;
}