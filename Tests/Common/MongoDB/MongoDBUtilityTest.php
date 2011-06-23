<?php
namespace ENC\Bundle\BackupRestoreBundle\Tests\Common\MongoDB;

use ENC\Bundle\BackupRestoreBundle\Common\MongoDB\MongoDBUtility;

class MongoDBUtilityTest extends \PHPUnit_Framework_TestCase
{
    protected $utilityInstance;
    
    public function setUp()
    {
        if (is_null($this->utilityInstance)) {
            $this->utilityInstance = new MongoDBUtility();
        }
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_extractParametersFromServerString_passingANonStringArgument_throwsException()
    {
        $this->utilityInstance->extractParametersFromServerString(123);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_extractParametersFromServerString_passingAnInvalidHostStringArgument_throwsException()
    {
        $this->utilityInstance->extractParametersFromServerString('it_should_start_with_protocol');
    }
    
    public function test_extractParametersFromServerString_passingStringWithHostnameAndPort_returnsCorrectParameters()
    {
        $serverString = 'mongodb://127.0.0.1:1234';
        
        $parameters = $this->utilityInstance->extractParametersFromServerString($serverString);
        
        $this->assertInternalType('array', $parameters);
        $this->assertArrayHasKey('hostname', $parameters);
        $this->assertArrayHasKey('username', $parameters);
        $this->assertArrayHasKey('password', $parameters);
        $this->assertEquals(count($parameters), 3);
        $this->assertEquals($parameters['hostname'], '127.0.0.1:1234');
        $this->assertEquals($parameters['username'], '');
        $this->assertEquals($parameters['password'], '');
    }
    
    public function test_extractParametersFromServerString_passingStringWithMultipleHostnames_returnsCorrectParametersForTheFirstHostname()
    {
        $serverString = 'mongodb://127.0.0.1:1234,127.0.0.1:1235';
        $parsedHostnameShouldBe = '127.0.0.1:1234';
        
        $parameters = $this->utilityInstance->extractParametersFromServerString($serverString);
        
        $this->assertInternalType('array', $parameters);
        $this->assertArrayHasKey('hostname', $parameters);
        $this->assertArrayHasKey('username', $parameters);
        $this->assertArrayHasKey('password', $parameters);
        $this->assertEquals(count($parameters), 3);
        $this->assertEquals($parameters['hostname'], $parsedHostnameShouldBe);
        $this->assertEquals($parameters['username'], '');
        $this->assertEquals($parameters['password'], '');
    }
    
    public function test_extractParametersFromServerString_passingStringWithMultipleHostnamesAndUsernameAndPasswords_returnsCorrectParametersForTheFirstHostnameAndUsernameAndPassword()
    {
        $serverString = 'mongodb://username:password@127.0.0.1:1234,username:password@127.0.0.1:1235';
        $parsedHostnameShouldBe = '127.0.0.1:1234';
        $parsedUsernameShouldBe = 'username';
        $parsedPasswordShouldBe = 'password';
        
        $parameters = $this->utilityInstance->extractParametersFromServerString($serverString);
        
        $this->assertInternalType('array', $parameters);
        $this->assertArrayHasKey('hostname', $parameters);
        $this->assertArrayHasKey('username', $parameters);
        $this->assertArrayHasKey('password', $parameters);
        $this->assertEquals(count($parameters), 3);
        $this->assertEquals($parameters['hostname'], $parsedHostnameShouldBe);
        $this->assertEquals($parameters['username'], $parsedUsernameShouldBe);
        $this->assertEquals($parameters['password'], $parsedPasswordShouldBe);
    }
}