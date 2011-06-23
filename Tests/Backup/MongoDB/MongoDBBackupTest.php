<?php
namespace ENC\Bundle\BackupRestoreBundle\Tests\Backup\MongoDB;

use ENC\Bundle\BackupRestoreBundle\Factory\BackupRestoreFactory;
use ENC\Bundle\BackupRestoreBundle\Tests\Backup\TestBackupFactory;
use ENC\Bundle\BackupRestoreBundle\Tests\Factory\TestBackupFactoryFactory;

class MongoDBBackupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_backupDatabase_passingInvalidDirectory_throwsInvalidArgumentException()
    {
        $backupInstance = TestBackupFactory::createMock('mongodb', array(
            'callVendorBackupTool'
        ));
        
        $backupInstance->backupDatabase('invalidDir');
    }
    
    public function test_backupDatabase_passingValidDirectory_returnsPathToBackupIfNoExceptionWasThrown()
    {
        $tmpDir = sys_get_temp_dir();
        $pathToBackupMustBe = $tmpDir.'/dump';
        
        $backupInstance = TestBackupFactory::createMock('mongodb', array(
            'doCallVendorBackupTool'
        ));
        $backupInstance->expects($this->once())
            ->method('doCallVendorBackupTool')
            ->with($this->equalTo($pathToBackupMustBe))
            ->will($this->returnValue(true));
        
        $pathToBackup = $backupInstance->backupDatabase($tmpDir);
        
        $this->assertEquals($pathToBackup, $pathToBackupMustBe);
    }
    
    /**
     * @expectedException ENC\Bundle\BackupRestoreBundle\Exception\BackupException
     */
    public function test_callVendorBackupTool_throwsBackupExceptionIfSomethingWentWrong()
    {
        $tmpDir = sys_get_temp_dir();
        $pathToBackupMustBe = $tmpDir.'/dump';
        
        $backupInstance = TestBackupFactory::createMock('mongodb', array(
            'doCallVendorBackupTool', 'getLastCommandOutput'
        ));
        $backupInstance->expects($this->once())
            ->method('getLastCommandOutput')
            ->will($this->returnValue(array()));
        $backupInstance->expects($this->once())
            ->method('doCallVendorBackupTool')
            ->with($this->equalTo($pathToBackupMustBe))
            ->will($this->returnValue(false));
        
        $backupInstance->backupDatabase($tmpDir);
    }
}