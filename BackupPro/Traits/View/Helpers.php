<?php
/**
 * mithra62
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/Traits/View/Helpers.php
 */
namespace mithra62\BackupPro\Traits\View;

trait Helpers
{
    protected $bp_options = array(
        'db_restore_methods' => array(
            'php' => 'php',
            'mysql' => 'mysql'
        )
    );
    
    public function m62Options($type, $translate = true)
    {
        $this->options = $this->options + $this->bp_options;
        return parent::m62Options($type, $translate);
    }
}