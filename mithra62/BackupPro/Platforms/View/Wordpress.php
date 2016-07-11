<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Wordpress.php
 */
 
namespace mithra62\BackupPro\Platforms\View;

use JaegerApp\Platforms\View\Wordpress as WordpressView;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

/**
 * Backup Pro - Wordpress View abstraction
 *
 * Contains the Wordpress specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Wordpress extends WordpressView
{
    use ViewHelpers;
}