<?php

namespace ENC\Bundle\BackupRestoreBundle\Tests\Restore;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use ENC\Bundle\BackupRestoreBundle\Restore;

class TestRestoreFactory extends WebTestCase
{
    public static function createMock($platform, array $methods = array(), array $constructorArguments = array())
    {
        $instance = new self();
        
        switch ($platform) {
            case 'mysql':
                $constructorArguments = empty($constructorArguments) ? array(self::getDbalConnectionMock()) : $constructorArguments;
                
                return $instance->getMock('ENC\Bundle\BackupRestoreBundle\Restore\MySql\MySqlRestore', $methods, $constructorArguments, '');
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
}
