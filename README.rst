BackupRestoreBundle
===================

A bundle which provides helper classes and commands to run DB vendor's utilities to backup and restore databases. For now the following DB vendors are implemented:

* MySQL

Backups
-------

In order to make a backup you have two choices. First you can run a simple command:::

    php app/console_dev database:backup "your-service-connection-id" "path/destination/for/generated/sql" "optional_filename.sql"

For example:::

    php app/console_dev database:backup "doctrine.dbal.default_connection" "/var/backups" "new_backup.sql"

The other option is to make a backup from PHP:::

    $factory = $container->get('backup_restore.factory');
    $backupInstance = $factory->getBackupInstance($myConnectionServiceId);
    $backupInstance('/var/backups', 'new_backup.sql');

Restore
-------

To make a restore from a SQL file you have two choices too. Running a command:::

    php app/console_dev database:restore "your-service-connection-id" "/path/to/backup_file.sql"

Or from PHP:::

    $factory = $container->get('backup_restore.factory');
    $restoreInstance = $factory->getRestoreInstance($myConnectionServiceId);
    $restoreInstance('/path/to/backup_file.sql');

TODO
----

* Implement more DB vendor's utilities