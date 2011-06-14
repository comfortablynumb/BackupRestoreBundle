BackupRestoreBundle
===================

A bundle which provides helper classes and commands to run DB vendor's utilities to backup and restore databases. For now the following DB vendors are implemented:

* MySQL
* MongoDB

Installation
------------

You can install the bundle as usual. First you clone the repo of the bundle: ::

    git clone git://github.com/comfortablynumb/BackupRestoreBundle.git vendor/bundles/ENC/Bundle/BackupRestoreBundle

Then add the path to the ENC folder in your autoload.php: ::

    // autoload.php
    $loader->registerNamespaces(array(
        // Rest of bundles..
        
        'ENC' => __DIR__.'/../vendor/bundles'
    ));
    
Register the bundle in your AppKernel.php: ::

    // AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // Rest of bundles..
            new ENC\Bundle\BackupRestoreBundle\BackupRestoreBundle()
        );
    }

And finally, add this to your config.yml: ::

    # config.yml
    backup_restore: ~

Now you should be ready to go.

SQL Databases
-------------

Backup:
#######

To make a backup you have two choices. First you can run a simple command: ::

    php app/console_dev database:backup "your-service-connection-id" "path/destination/for/generated/sql" "optional_filename.sql"

For example: ::

    php app/console_dev database:backup "doctrine.dbal.default_connection" "/var/backups" "new_backup.sql"

The other option is to make a backup from PHP: ::

    $factory = $container->get('backup_restore.factory');
    $backupInstance = $factory->getBackupInstance($myConnectionServiceId);
    $backupInstance->backupDatabase('/var/backups', 'new_backup.sql');

Restore:
########

To make a restore from a SQL file you have two choices too. Running a command: ::

    php app/console_dev database:restore "your-service-connection-id" "/path/to/backup_file.sql"

Or from PHP: ::

    $factory = $container->get('backup_restore.factory');
    $restoreInstance = $factory->getRestoreInstance($myConnectionServiceId);
    $restoreInstance->restoreDatabase('/path/to/backup_file.sql');

MongoDB
-------

Backup:
#######

Making a backup for MongoDB just needs a different approach. You need only the directory to which the backup will be deployed. The utility used for this 
action is "mongodump", which creates a directory named "dump" with the backup. You just need to pass a directory path where this backup will be created: ::

    php app/console_dev database:backup "your-mongodb-service-connection-id" "/var/backups"

Or in PHP: ::

    $factory = $container->get('backup_restore.factory');
    $backupInstance = $factory->getBackupInstance($myMongoDBConnectionServiceId);
    
    // Note that the file argument is not used!
    $backupInstance->backupDatabase('/var/backups');

Restore:
########

The same goes for the restore action. Instead of a file, you need to pass the path to the directory containing the backup: ::

    php app/console_dev database:restore "your-mongodb-service-connection-id" "/path/to/backup_dir_of_mongodb"

Or from PHP: ::

    $factory = $container->get('backup_restore.factory');
    $restoreInstance = $factory->getRestoreInstance($myMongoDBConnectionServiceId);
    $restoreInstance->restoreDatabase('/path/to/backup_dir_of_mongodb');

TODO
----

* Implement more DB vendor's utilities
* More Unit Tests