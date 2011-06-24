<?php
namespace ENC\Bundle\BackupRestoreBundle\Backup\MySql;

use ENC\Bundle\BackupRestoreBundle\Backup\AbstractBackup;
use ENC\Bundle\BackupRestoreBundle\Exception\BackupException;

class MySqlBackup extends AbstractBackup
{
    public function backupDatabase($targetDirectory, $fileName = null)
    {
        if (!is_dir($targetDirectory)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" is not valid or it doesn\'t exist.', $targetDirectory));
        }
        
        if ($fileName !== null && !is_string($fileName)) {
            throw new \InvalidArgumentException(sprintf('File name "%s" is not valid or it doesn\'t exist.', $targetDirectory));
        }
        
        $connection	= $this->getConnection();
        $backupFilename	= is_null($fileName) ? 'backup-mysql-'.strtolower(str_replace('_', '-', $connection->getDatabase())).'-'.date( 'Y-m-d-H-i-s' ).'.sql' : $fileName;
        $backupFilePath	= $targetDirectory.DIRECTORY_SEPARATOR.$backupFilename;
        
        $this->callVendorBackupTool($backupFilePath);
        
        return $backupFilePath;
    }
    
    protected function callVendorBackupTool($backupFilePath)
    {
        if (!$this->doCallVendorBackupTool($backupFilePath)) {
            $exception = new BackupException('An error occurred while working on the backup. For more details, please look at the output of the command using the "getOutput" method of the exception.');
            $exception->setOutput($this->getLastCommandOutput());
            
            throw $exception;
        }
    }
    
    protected function doCallVendorBackupTool($backupFilePath)
    {
        $connection = $this->getConnection();
        $returnValue = '';
        $output = array();
        $returnLine = exec(sprintf('mysqldump --opt --single-transaction --user="%s" --password="%s" --host="%s" --port="%s" %s > %s', 
            $connection->getUsername(), 
            $connection->getPassword(), 
            $connection->getHost(), 
            $connection->getPort(), 
            $connection->getDatabase(), $backupFilePath), $output, $returnValue);
        
        $this->setLastCommandOutput($output);
        
        if ($returnValue !== 0) {
            return false;
        } else {
            return true;
        }
    }
}