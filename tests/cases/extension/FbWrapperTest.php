<?php

namespace li3_facebook\tests\cases\extension;

use Exception;
use li3_facebook\extension\FbWrapper;
use \lithium\core\Libraries;


class FbWrapperTest extends \lithium\test\Unit {
	
	protected $mockConfig = array(
		'appId' => 'ab',
		'secret' => 'secret',
	);
	

	public function testEmptyConfiguration(){
		//$original_conf = Libraries::get('li3_facebook');
		
		$disableInit = array('init' => false);
		$newConfig = array() + $disableInit;

		$expectedException = 'Configuration: `appId` should be set';
		$this->expectException($expectedException);
		$fbWrapper = new FbWrapper($newConfig);
		$this->assertTrue($fbWrapper->checkConfiguration(),'App Id Exception should be thrown.');
	}
	
	public function testSecretConfiguration(){
		$disableInit = array('init' => false);
		$newConfig = array() + $disableInit;
		
		$newConfig = array('appId' => "l2k") + $newConfig;
		$expectedException = 'Configuration: `secret` should be set';
		$fbWrapper = new FbWrapper($newConfig);
		$this->expectException($expectedException);
		$this->assertTrue($fbWrapper->checkConfiguration(),'Secret Exception should be thrown.');
	}
	
	public function testCookieNotYetImplementedException(){
		$disableInit = array('init' => false);
		$newConfig = $this->mockConfig + $disableInit;
		$cookie = array('cookie' => true) + $newConfig;
		$domain = array('domain' => true) + $newConfig;
		$fileUpload = array('fileUpload' => true) + $newConfig;
		
		$expectedException = 'Configuration: `cookie` not yet suported';
		$fbWrapper = new FbWrapper($cookie);
		$this->expectException($expectedException);
		$this->assertTrue($fbWrapper->checkConfiguration());
	}
	
	public function testDomainNotYetImplementedException(){
		$disableInit = array('init' => false);
		$newConfig = $this->mockConfig + $disableInit;
		$domain = array('domain' => true) + $newConfig;
		
		$expectedException = 'Configuration: `domain` not yet supported';
		$fbWrapper = new FbWrapper($domain);
		$this->expectException($expectedException);
		$this->assertTrue($fbWrapper->checkConfiguration());
	}
	
	public function testFileUploadNotYetImplementedException(){	
		$disableInit = array('init' => false);
		$newConfig = $this->mockConfig + $disableInit;
		$fileUpload = array('fileUpload' => true) + $newConfig;
		
		$expectedException = 'Configuration: `fileUpload` not yet supported';
		$fbWrapper = new FbWrapper($fileUpload);
		$this->expectException($expectedException);
		$this->assertTrue($fbWrapper->checkConfiguration());	
	}
	
	public function testGetInstance(){
		//test against simple Construction
		/*try{
			
			FbWrapper::_getInstance();
		}catch(Exception $e){
			$this->assertFalse(true,$e->getMessage());
		}*/
		
		//check against unset Compatibility
		FbWrapper::$__compatibleVersions = array();
		$expectedErrorString = 'Facebook Library is not compatible to our library';
		$this->expectException($expectedErrorString);
		$this->assert(FbWrapper::checkRequirements(),'Facebook Library Versions shouldnt match');

		//check aginst false Value
		FbWrapper::$__compatibleVersions = array('1.1.2');
		$expectedError = 'Facebook Library is not compatible to our library';
		$this->expectException($expectedError);
		$this->assert(FbWrapper::checkRequirements(),'Facebook Library Versions shouldnt match');
		
	}
}
?>