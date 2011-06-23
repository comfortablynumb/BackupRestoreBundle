<?php

namespace ENC\Bundle\BackupRestoreBundle\Exception;

class CommandException extends \Exception
{
    protected $output;
    
    public function setOutput(array $output)
    {
        $this->output = $output;
    }
    
    public function getOutput()
    {
        return $this->output;
    }
}