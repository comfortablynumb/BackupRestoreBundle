<?php

namespace ENC\Bundle\BackupRestoreBundle\Tests\Factory;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use ENC\Bundle\BackupRestoreBundle\Factory\BackupRestoreFactory;

class TestBackupRestoreFactoryFactory extends WebTestCase
{
    protected static $container = null;

    public static function create(array $services = array())
    {
        $container = self::getNewContainer($services);
        $factory = new BackupRestoreFactory($container);
         
        return $factory;
    }
    
    public static function getNewContainer(array $services = array())
    {
        $container = new Container();
        
        foreach ($services as $id => $service) {
            $container->set($id, $service);
        }
        
        return $container;
    }
    
    public static function getDbalConnectionMock(array $methods = array())
    {
        $instance = new self();
        
        return $instance->getMock('Doctrine\DBAL\Connection', $methods, array(), '', false);
    }
}
