<?php
namespace mithra62\BackupPro;

class Rest
{
    protected $lang = null;
    protected $platform = null;
    protected $server = null;
    
    public function getServer()
    {
        if(is_null($this->server))
        {
            $this->server = new Rest\Server($this->platform, $this);
            //$this->server
        }
        
        return $this->server;
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