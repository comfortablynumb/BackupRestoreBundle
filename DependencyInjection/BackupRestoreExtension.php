<?php

namespace ENC\Bundle\BackupRestoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class BackupRestoreExtension extends Extension
{
    public function load( array $config, ContainerBuilder $container )
    {
        $loader = new XmlFileLoader( $container, new FileLocator(__DIR__ . '/../Resources/config' ) );
        $loader->load( 'config.xml' );
    }
}
