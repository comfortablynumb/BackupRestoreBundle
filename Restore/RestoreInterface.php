<?php
namespace ENC\Bundle\BackupRestoreBundle\Restore;

interface RestoreInterface
{
    public function restoreDatabase($file);
}