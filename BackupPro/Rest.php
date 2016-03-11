<?php
namespace mithra62\BackupPro;

use mithra62\Rest AS m62Rest;

class Rest extends m62Rest
{
    public function getServer()
    {
        if(is_null($this->server))
        {
            $this->server = new Rest\Server($this->platform, $this);
            //$this->server
        }
    
        return $this->server;
    }
}