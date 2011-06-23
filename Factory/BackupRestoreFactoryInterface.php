<?php
namespace ENC\Bundle\BackupRestoreBundle\Factory;

interface BackupRestoreFactoryInterface
{
    public function getBackupInstance($serviceConnectionId);
    public function getRestoreInstance($serviceConnectionId);
}