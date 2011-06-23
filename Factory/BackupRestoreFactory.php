<?php
namespace ENC\Bundle\BackupRestoreBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;

use ENC\Bundle\BackupRestoreBundle\Backup;
use ENC\Bundle\BackupRestoreBundle\Restore;
use ENC\Bundle\BackupRestoreBundle\Exception\UnsupportedPlatformException;
use ENC\Bundle\BackupRestoreBundle\Exception\UnsupportedDbalPlatformException;

class BackupRestoreFactory implements BackupRestoreFactoryInterface
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    protected function getContainer()
    {
        return $this->container;
    }
    
    public function getBackupInstance($serviceConnectionId)
    {
        $connection = $this->getConnection($serviceConnectionId);
        
        if ($connection instanceof \Doctrine\DBAL\Connection) {
            $platform = $connection->getDatabasePlatform();
            
            if ($platform instanceof \Doctrine\DBAL\Platforms\MysqlPlatform) {
                return new Backup\Mysql\MysqlBackup($connection);
            } else {
                throw new UnsupportedDbalPlatformException('The database platform selected for this DBAL connection is not yet supported for this bundle.');
            }
        } else if ($connection instanceof \Doctrine\MongoDB\Connection) {
            throw new UnsupportedPlatformException('The database platform selected is not yet supported for this bundle.');
        } else {
            throw new UnsupportedPlatformException('The database platform selected is not yet supported for this bundle.');
        }
        
        return $backupInstance;
    }
    
    public function getRestoreInstance($serviceConnectionId)
    {
        $connection = $this->getConnection($serviceConnectionId);
        
        if ($connection instanceof \Doctrine\DBAL\Connection) {
            $platform = $connection->getDatabasePlatform();
            
            if ($platform instanceof \Doctrine\DBAL\Platforms\MysqlPlatform) {
                return new Restore\Mysql\MysqlRestore($connection);
            } else {
                throw new UnsupportedDbalPlatformException('The database platform selected for this DBAL connection is not yet supported for this bundle.');
            }
        } else if ($connection instanceof \Doctrine\MongoDB\Connection) {
            throw new UnsupportedPlatformException('The database platform selected is not yet supported for this bundle.');
        } else {
            throw new UnsupportedPlatformException('The database platform selected is not yet supported for this bundle.');
        }
    }
    
    protected function getConnection($serviceConnectionId)
    {
        if (!is_string($serviceConnectionId)) {
            throw new \InvalidArgumentException('Connection service ID must be a string containing the ID of the connection service to backup / restore.');
        }
        
        try { 
            $connection = $this->getContainer()->get($serviceConnectionId);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Connection service ID provided does not refer to a valid connection service.');
        };
        
        return $connection;
    }
}