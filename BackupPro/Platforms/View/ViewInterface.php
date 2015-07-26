<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/ViewInterface.php
 */
 
namespace mithra62\BackupPro\Platforms\View;

interface ViewInterface
{
    /**
     * Just passes to the Language object for translation
     * @param string $string The language key to translate
     * @return \mithra62\string
     */
    public function m62Lang($string);
    
    /**
     * Formats a file value into a human readable format
     * @param string $string
     * @return \mithra62\string
     */
    public function m62FileSize($string);
    
    /**
     * Formats a date
     * @param string $date
     * @param string $html
     * @return string
     */
    public function m62DateTime($date, $html = true);
    
    /**
     * Encodes a string securely
     * @param string $string
     * @return string
     */
    public function m62Encode($string);
    
    /**
     * Decodes a string securely
     * @param string $string
     * @return string
     */
    public function m62Decode($string);    
}