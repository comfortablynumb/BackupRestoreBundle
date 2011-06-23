<?php

namespace ENC\Bundle\BackupRestoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BackupCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('database:backup')
            ->setDefinition(array(
                new InputArgument('connection-service-id', InputArgument::REQUIRED, 'The connection service ID of the database from which you want to generate a backup sql file.'),
                new InputArgument('target-dir', InputArgument::REQUIRED, 'The directory where the backup file will be saved.'),
                new InputArgument('filename', InputArgument::OPTIONAL, 'The name for the backup file.')
            ))
            ->setHelp(<<<EOT
The <info>database:backup</info> command generates a backup of a 
database using the connection you choose. Note that the database platform 
must be supported by the bundle. Check if the database platform you want 
to generate a backup from is supported.

An example of usage of the command:

<info>./app/console database:backup "my-connection-service-id" "/var/tmp" [my_sql_file_name.sql]</info>

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $factory = $container->get('backup_restore.factory');
        $connectionServiceId = $input->getArgument('connection-service-id');
        $directory = $input->getArgument('target-dir');
        $fileName = $input->getArgument('filename') ? $input->getArgument('filename') : null;
        
        $backupInstance = $factory->getBackupInstance($connectionServiceId);
        
        $backupPath = $backupInstance->backupDatabase($directory, $fileName);
        
        $connection = $container->get($connectionServiceId);
        
        $output->writeln(sprintf('<comment>></comment> <info>Backup was successfully created in "%s".</info>', $backupPath));
    }
}
