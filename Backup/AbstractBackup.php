<?php
namespace ENC\Bundle\BackupRestoreBundle\Backup;

abstract class AbstractBackup implements BackupInterface
{
    protected $connection;
    protected $lastCommandOutput;
    
    /**
     * There's no common Connection interface for DBAL connections and others 
     * like MongoDB's Connection class at the moment. So for now we leave it 
     * this way. If we didn't do it this way then we could have duplicated code 
     * in every Backup's implementation
     */
    public function __construct($connection)
    {
        $this->setConnection($connection);
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }
    
    protected function setLastCommandOutput(array $output)
    {
        $this->lastCommandOutput = $output;
    }
    
    public function getLastCommandOutput()
    {
        return $this->lastCommandOutput;
    }
}