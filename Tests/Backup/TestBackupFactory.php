<?php

namespace ENC\Bundle\BackupRestoreBundle\Tests\Backup;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use ENC\Bundle\BackupRestoreBundle\Backup;
use ENC\Bundle\BackupRestoreBundle\Factory\BackupRestoreFactory;

class TestBackupFactory extends WebTestCase
{
    public static function createMock($platform, array $methods = array(), array $constructorArguments = array())
    {
        $instance = new self();
        
        switch ($platform) {
            case 'mysql':
                $constructorArguments = empty($constructorArguments) ? array(self::getDbalConnectionMock()) : $constructorArguments;
                
                return $instance->getMock('ENC\Bundle\BackupRestoreBundle\Backup\Mysql\MysqlBackup', $methods, $constructorArguments, '');
            case 'mongodb':
                $constructorArguments = empty($constructorArguments) ? array(self::getMongoDBConnectionMock()) : $constructorArguments;
                
                return $instance->getMock('ENC\Bundle\BackupRestoreBundle\Backup\MongoDB\MongoDBBackup', $methods, $constructorArguments, '');
            default:
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid database platform or is not supported by this bundle.', $platform));
                
                break;
        }
    }
    
    public static function getDbalConnectionMock(array $methods = array())
    {
        $instance = new self();
        
        return $instance->getMock('Doctrine\DBAL\Connection', $methods, array(), '', false);
    }
    
    public static function getMongoDBConnectionMock(array $methods = array())
    {
        $instance = new self();
        
        return $instance->getMock('Doctrine\MongoDB\Connection', $methods, array(), '', false);
    }
}
