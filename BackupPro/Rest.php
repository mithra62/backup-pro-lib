<?php
namespace mithra62\BackupPro;

class Rest
{
    protected $lang = null;
    protected $platform = null;
    
    public function getServer()
    {
        return new Rest\Server($this->platform);
    }
    
    public function setPlatform(\mithra62\Platforms\AbstractPlatform $platform)
    {
        $this->platform = $platform;
        return $this;
    }
    
    public function setLang(\mithra62\Language $lang)
    {
        $this->lang = $lang;
        return $this;
    }
}