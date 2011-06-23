<?php

namespace ENC\Bundle\BackupRestoreBundle\Common\MongoDB;

class MongoDBUtility
{
    public function extractParametersFromServerString($serverString)
    {
        if (!is_string($serverString)) {
            throw new \InvalidArgumentException('First argument must be the server string of MongoDB.');
        }
        
        if (strpos($serverString, 'mongodb://') !== 0) {
            $msg = 'The string received doesn\'t seem to be a valid server string for MongoDB. ';
            $msg .= 'Did you forget to add "mongodb://" at the start of the string?.';
            
            throw new \InvalidArgumentException($msg);
        }
        
        $parameters = array(
            'hostname'      => '',
            'username'      => '',
            'password'      => ''
        );
        
        $serverString = str_replace('mongodb://', '', $serverString);
        
        if (($pos = strpos($serverString, ',')) !== false) {
            // For now, if there are multiple servers we'll use just 
            // the first server. The rest is stripped
            $serverString = substr($serverString, 0, $pos);
        }
        
        if (($pos = strpos($serverString, '@')) !== false) {
            $userAndPass = substr($serverString, 0, $pos);
            $serverString = substr($serverString, $pos + 1);
            
            if (($pos = strpos($userAndPass, ':')) !== false) {
                $userAndPass = explode(':', $userAndPass);
                $parameters['username'] = $userAndPass[0];
                $parameters['password'] = $userAndPass[1];
            }
        }
        
        $parameters['hostname'] = $serverString;
        
        return $parameters;
    }
}