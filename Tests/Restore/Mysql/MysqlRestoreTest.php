<?php
namespace ENC\Bundle\BackupRestoreBundle\Tests\Restore\Mysql;

use ENC\Bundle\BackupRestoreBundle\Tests\Restore\TestRestoreFactory;
use ENC\Bundle\BackupRestoreBundle\Exception\FileException;

class MysqlRestoreTest extends \PHPUnit_Framework_TestCase
{
    protected $tmpFile;
    
    public function setUp()
    {
        $this->tmpFile = $this->createTmpFile();
    }
    
    public function tearDown()
    {
        @unlink($this->tmpFile);
        $this->tmpFile = null;
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_restoreDatabase_passingNonStringArgument_throwsInvalidArgumentException()
    {
        $restoreInstance = TestRestoreFactory::createMock('mysql', array(
            'callRestoreVendorTool',
            'doCallRestoreVendorTool'
        ));
        
        $restoreInstance->restoreDatabase(123);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_restoreDatabase_passingInvalidFile_throwsInvalidArgumentException()
    {
        $restoreInstance = TestRestoreFactory::createMock('mysql', array(
            'callRestoreVendorTool',
            'doCallRestoreVendorTool'
        ));
        
        $restoreInstance->restoreDatabase('invalidFile');
    }
    
    public function test_restoreDatabase_callsCallVendorBackupToolInternallyWithCorrectArguments()
    {
        $restoreInstance = TestRestoreFactory::createMock('mysql', array('callVendorRestoreTool'));
        $restoreInstance->expects($this->once())
            ->method('callVendorRestoreTool')
            ->with($this->tmpFile);
        
        $restoreInstance->restoreDatabase($this->tmpFile);
    }
    
    /**
     * @expectedException ENC\Bundle\BackupRestoreBundle\Exception\RestoreException
     */
    public function test_callVendorRestoreTool_throwsRestoreExceptionIfDoCallVendorRestoreToolProducedErrors()
    {
        $restoreInstance = TestRestoreFactory::createMock('mysql', array('getLastCommandOutput', 'doCallVendorRestoreTool'));
        $restoreInstance->expects($this->once())
            ->method('getLastCommandOutput')
            ->will($this->returnValue(array()));
        $restoreInstance->expects($this->once())
            ->method('doCallVendorRestoreTool')
            ->with($this->tmpFile)
            ->will($this->returnValue(false));
        
        $restoreInstance->restoreDatabase($this->tmpFile);
    }
    
    
    
    // Utility Methods
    public function createTmpFile()
    {
        $tmpDir = sys_get_temp_dir();
        $fileName = 'test_tmp_'.time().'_'.rand(1000, 9999).'.sql';
        $fullPath = $tmpDir.DIRECTORY_SEPARATOR.$fileName;
        
        if (!$handle = fopen($fullPath, 'w')) {
            throw new \RuntimeException('Tmp file for test could not be created.');
        }
        
        fclose($handle);
        
        return $fullPath;
    }
}