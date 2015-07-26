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

use mithra62\Traits\DateTime;

/**
 * Backup Pro - Eecms View Object
 *
 * Contains the view helpers for ExpressionEngine
 *
 * @package 	BackupPro\View
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Eecms implements ViewInterface
{
    use DateTime;
    
    /**
     * Whether to
     * @var unknown
     */
    public $autoescape = false;
    
    /**
     * The Language object
     * @var \mithra62\Language
     */
    private $lang = null;
    
    /**
     * The File object
     * @var \mithra62\Files
     */
    private $file = null;
    
    /**
     * The File object
     * @var \mithra62\Settings
     */
    private $settings = null;
    
    /**
     * The File object
     * @var \mithra62\Encrypt
     */
    private $encrypt = null;
    
    /**
     * The Platform object
     * @var \mithra62\Platforms
     */
    private $platform = null;

    /**
     * Set it up
     * @param \mithra62\Language $lang
     * @param \mithra62\Files $file
     * @param \mithra62\Settings $setting
     * @param \mithra62\Encrypt $encrypt
     * @param \mithra62\Platforms $platform
     */
    public function __construct(
        \mithra62\Language $lang,
        \mithra62\Files $file,
        \mithra62\Settings $setting,
        \mithra62\Encrypt $encrypt,
        \mithra62\Platforms $platform)
    {
        $this->lang = $lang;
        $this->file = $file;
        $this->settings = $setting->get();
        $this->encrypt = $encrypt;
        $this->platform = $platform;
        $this->setTz($this->platform->getTimezone());
    }    
    
    /**
     * Just passes to the Language object for translation
     * @param string $string The language key to translate
     * @return \mithra62\string
     */
    public function m62Lang($string)
    {
        return $this->lang->__($string);
    }
    
    /**
     * Formats a file value into a human readable format
     * @param string $string
     * @return \mithra62\string
     */
    public function m62FileSize($string)
    {
        return $this->file->filesizeFormat($string);
    }
    
    /**
     * Formats a date
     * @param string $date
     * @param string $html
     * @return string
     */
    public function m62DateTime($date, $html = true)
    {
        if($this->settings['relative_time'] == '1')
        {
            if($html)
            {
                $date = '<span class="backup_pro_timeago" title="'.$this->convertTimestamp($date, $this->settings['date_format']).'">'.$this->getRelativeDateTime($date).'</span>';
            }
            else
            {
                $date = $this->getRelativeDateTime($date);
            }
        }
        else
        {
            $date = $this->convertTimestamp($date, $this->settings['date_format']);
        }
    
        return $date;
    }
    
    /**
     * Encodes a string securely
     * @param string $string
     * @return string
     */
    public function m62Encode($string)
    {
        return $this->encrypt->encode($string);
    }
    
    /**
     * Decodes a string securely
     * @param string $string
     * @return string
     */
    public function m62Decode($string)
    {
        return $this->encrypt->decode($string);
    }
}