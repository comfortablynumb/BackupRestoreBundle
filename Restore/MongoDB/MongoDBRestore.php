<?php
namespace ENC\Bundle\BackupRestoreBundle\Restore\MongoDB;

use ENC\Bundle\BackupRestoreBundle\Common\MongoDB\MongoDBUtility;
use ENC\Bundle\BackupRestoreBundle\Restore\AbstractRestore;
use ENC\Bundle\BackupRestoreBundle\Exception\RestoreException;

class MongoDBRestore extends AbstractRestore
{
    protected $utility;
    
    public function __construct($connection)
    {
        parent::__construct($connection);
        
        $this->utility = new MongoDBUtility();
    }
    
    public function restoreDatabase($directory)
    {
        if (!is_string($directory)) {
            throw new \InvalidArgumentException('First argument must be a string with the path to the directory which has the backup files of MongoDB.');
        }

        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" does not exist.', $directory));
        }
        
        $this->callVendorRestoreTool($directory);
    }
    
    public function callVendorRestoreTool($directory)
    {
        if (!$this->doCallVendorRestoreTool($directory)) {
            $exception = new RestoreException('An error occurred while working on the restore of your database. For more details, please look at the output of the command using the "getOutput" method of the exception.');
            $exception->setOutput($this->getLastCommandOutput());
            
            throw $exception;
        }
    }
    
    protected function doCallVendorRestoreTool($directory)
    {
        $connection = $this->getConnection();
        $serverParameters = $this->utility->extractParametersFromServerString($connection->getServer());
        $returnValue = '';
        $output = array();
        
        $commandToExecute = sprintf('mongorestore --drop --host "%s" %s %s %s', 
            $serverParameters['hostname'], 
            ($serverParameters['username'] !== '' ? '--username '.$serverParameters['username'] : ''),
            ($serverParameters['password'] !== '' ? '--password '.$serverParameters['password'] : ''),
            $directory);
        
        $returnLine = exec($commandToExecute, $output, $returnValue);
        
        $this->setLastCommandOutput($output);
        
        if ($returnValue !== 0) {
            return false;
        } else {
            return true;
        }
    }
}