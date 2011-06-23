<?php

namespace ENC\Bundle\BackupRestoreBundle\Common\MongoDB;

class MongoDBUtil
{
    public function extractParametersFromServerString($serverString)
    {
        $parameters = array(
            'hostname'      => '',
            'username'      => '',
            'password'      => ''
        );
        
        $serverString = str_replace('mongodb://', '', $serverString);
        
        if (($pos = strpos($serverString, ',')) !== false) {
            // For now, if there are multiple servers we'll use just 
            // the first server.
            $serverString = substr($serverString, 0, $pos);
        }
        
        if (($pos = strpos($serverString, '@')) !== false) {
            $serverString = substr($serverString, $pos + 1);
            $userAndPass = substr($serverString, 0, $pos);
            
            if (($pos = strpos($userAndPass, ':')) !== false) {
                $userAndPass = exploit(':', $userAndPass);
                $parameters['username'] = $userAndPass[0];
                $parameters['password'] = $userAndPass[1];
            } else {
                $parameters['username'] = $userAndPass;
            }
        }
        
        $parameters['hostname'] = $serverString;
        
        return $parameters;
    }
}