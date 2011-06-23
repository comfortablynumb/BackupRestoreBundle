<?php
namespace ENC\Bundle\BackupRestoreBundle\Backup;

interface BackupInterface
{
    public function backupDatabase($targetDirectory, $fileName = null);
}