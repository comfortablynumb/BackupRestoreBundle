<?php
namespace ENC\Bundle\BackupRestoreBundle\Tests\Backup\Mysql;

use ENC\Bundle\BackupRestoreBundle\Factory\BackupRestoreFactory;
use ENC\Bundle\BackupRestoreBundle\Tests\Backup\TestBackupFactory;
use ENC\Bundle\BackupRestoreBundle\Tests\Factory\TestBackupFactoryFactory;

class MysqlBackupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_backupDatabase_passingInvalidDirectory_throwsInvalidArgumentException()
    {
        $backupInstance = TestBackupFactory::createMock('mysql', array(
            'callVendorBackupTool'
        ));
        
        $backupInstance->backupDatabase('invalidDir');
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_backupDatabase_passingInvalidFilename_throwsInvalidArgumentException()
    {
        $backupInstance = TestBackupFactory::createMock('mysql', array(
            'callVendorBackupTool'
        ));
        
        $backupInstance->backupDatabase(sys_get_temp_dir(), new \DateTime());
    }
    
    public function test_backupDatabase_callsCallVendorBackupToolInternallyWithCorrectArguments()
    {
        $targetDir = sys_get_temp_dir();
        $fileName = 'backup.sql';
        $fullFilePath = $targetDir.DIRECTORY_SEPARATOR.$fileName;
        
        $connectionMock = TestBackupFactory::getDbalConnectionMock();
        
        $backupInstance = TestBackupFactory::createMock('mysql', array('callVendorBackupTool'), array($connectionMock));
        $backupInstance->expects($this->once())
            ->method('callVendorBackupTool')
            ->with($fullFilePath);
        
        $filePath = $backupInstance->backupDatabase($targetDir, $fileName);
        
        $this->assertEquals($filePath, $fullFilePath);
    }
    
    public function test_backupDatabase_worksWithFileNameAsNull()
    {
        $targetDir = sys_get_temp_dir();
        
        $connectionMock = TestBackupFactory::getDbalConnectionMock();
        
        $backupInstance = TestBackupFactory::createMock('mysql', array('callVendorBackupTool'), array($connectionMock));
        $backupInstance->expects($this->once())
            ->method('callVendorBackupTool');
        
        $filePath = $backupInstance->backupDatabase($targetDir);
        $result = strpos($filePath, $targetDir) !== false;
        
        $this->assertTrue($result);
    }
    
    public function test_callVendorBackupTool_callsDoCallVendorBackupToolInternallyWithCorrectArguments()
    {
        $targetDir = sys_get_temp_dir();
        $fileName = 'backup.sql';
        $fullFilePath = $targetDir.DIRECTORY_SEPARATOR.$fileName;
        
        $connectionMock = TestBackupFactory::getDbalConnectionMock();
        
        $backupInstance = TestBackupFactory::createMock('mysql', array('doCallVendorBackupTool'), array($connectionMock));
        $backupInstance->expects($this->once())
            ->method('doCallVendorBackupTool')
            ->with($fullFilePath)
            ->will($this->returnValue(true));
        
        $filePath = $backupInstance->backupDatabase($targetDir, $fileName);
        
        $this->assertEquals($filePath, $fullFilePath);
    }
    
    /**
     * @expectedException ENC\Bundle\BackupRestoreBundle\Exception\BackupException
     */
    public function test_callVendorBackupTool_throwsBackupExceptionIfDoCallVendorBackupToolProducedErrors()
    {
        $targetDir = sys_get_temp_dir();
        $fileName = 'backup.sql';
        $fullFilePath = $targetDir.DIRECTORY_SEPARATOR.$fileName;
        
        $connectionMock = TestBackupFactory::getDbalConnectionMock();
        
        $backupInstance = TestBackupFactory::createMock('mysql', array('doCallVendorBackupTool', 'getLastCommandOutput'), array($connectionMock));
        $backupInstance->expects($this->once())
            ->method('doCallVendorBackupTool')
            ->with($fullFilePath)
            ->will($this->returnValue(false));
        $backupInstance->expects($this->once())
            ->method('getLastCommandOutput')
            ->will($this->returnValue(array()));
        
        $backupInstance->backupDatabase($targetDir, $fileName);
    }
}