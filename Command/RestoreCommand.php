<?php

namespace ENC\Bundle\BackupRestoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RestoreCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('database:restore')
            ->setDefinition(array(
                new InputArgument('connection-service-id', InputArgument::REQUIRED, 'The connection service ID of the database to which you want to put the restored data.'),
                new InputArgument('file', InputArgument::REQUIRED, 'The file to restore with.')
            ))
            ->setHelp(<<<EOT
The <info>database:restore</info> command restores a database using 
a file, presumably created with the command "database:backup" from 
this bundle.

An example of usage of the command:

<info>./app/console database:restore "my-connection-service-id" "/path/to/my/backup/file.sql"</info>

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $factory = $container->get('backup_restore.factory');
        $connectionServiceId = $input->getArgument('connection-service-id');
        $file = $input->getArgument('file');
        
        $restoreInstance = $factory->getRestoreInstance($connectionServiceId);
        
        $restoreInstance->restoreDatabase($file);
        
        $connection = $container->get($connectionServiceId);
        $databaseName = $connection->getDatabase();
        
        $output->writeln(sprintf('<comment>></comment> <info>Database "%s" was restored successfully.</info>', $databaseName));
    }
}
