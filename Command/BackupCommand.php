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
            ->addOption('connection-service-id', null, InputOption::VALUE_OPTIONAL, 'The connection service ID of the database from which you want to generate a backup sql file. (default is "doctrine.dbal.default_connection")', 'doctrine.dbal.default_connection')
            ->addOption('target-dir',            null, InputOption::VALUE_OPTIONAL, 'The directory where the backup file will be saved. (default is {root_dir}/backups)')
            ->addOption('filename',              null, InputOption::VALUE_OPTIONAL, 'The name for the backup file.')
            ->setHelp(<<<EOT
The <info>database:backup</info> command generates a backup of a 
database using the connection you choose. Note that the database platform 
must be supported by the bundle. Check if the database platform you want 
to generate a backup from is supported.

Examples of usage of the command:

<info>./app/console database:backup</info>
<info>./app/console database:backup --conection-service-id="my-connection-service-id" target-dir="/var/tmp" filename="my_sql_file_name.sql"</info>

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $factory = $container->get('backup_restore.factory');
        $connectionServiceId = $input->getOption('connection-service-id');
        $directory = $input->getOption('target-dir');
        
        if(!$directory)
        {
            $directory = $container->getParameter('kernel.root_dir') . '/backups';
        }
        
        $fileName = $input->getOption('filename') ? $input->getOption('filename') : null;
        
        $backupInstance = $factory->getBackupInstance($connectionServiceId);
        
        $backupPath = $backupInstance->backupDatabase($directory, $fileName);
        
        $connection = $container->get($connectionServiceId);
        
        $output->writeln(sprintf('<comment>></comment> <info>Backup was successfully created in "%s".</info>', $backupPath));
    }
}
