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
        ),
        's3_regions' => array(
            '0' => 'US Standard',
            'us-west-2' => 'Oregon',
            'us-west-1' => 'Northern California',
            'eu-west-1' => 'Ireland',
            'ap-southeast-1' => 'Singapore',
            'ap-northeast-1' => 'Tokyo',
            'ap-southeast-2' => 'Sydney',
            'sa-east-1' => 'Sao Paulo',
            'eu-central-1' => 'Frankfurt',
            'ap-northeast-2' => 'Seoul'
        )
    );
    
    public function m62Options($type, $translate = true)
    {
        $this->options = $this->options + $this->bp_options;
        return parent::m62Options($type, $translate);
    }
}