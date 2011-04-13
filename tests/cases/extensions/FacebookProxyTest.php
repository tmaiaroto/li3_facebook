<?php

namespace li3_facebook\tests\cases\extensions;

use li3_facebook\extensions\FacebookProxy;
use lithium\core\Libraries;

use Exception;

class FacebookProxyTest extends \lithium\test\Unit {

	protected $_mockDefaults = array(
		'appId' => '',
		'secret' => '',
		'cookie' => false,
		'domain' => false,
		'fileUpload' => false
	);

	public function setUp() {}

	public function tearDown() {}

	public function testReset(){
		FacebookProxy::reset();
		$this->assertEqual(FacebookProxy::config(), array(), 'configuration should be empty.');
	}

	//test configuration
	public function testConfig(){
		$oldConfig = Libraries::get('li3_facebook');

		Libraries::remove('li3_facebook');
		Libraries::add('li3_facebook');

		FacebookProxy::$_autoConfigure = false;
		FacebookProxy::__init();

		$this->assertEqual(FacebookProxy::config(),array(), 'config should be empty.');

		$this->assertEqual(FacebookProxy::config(array()),array(), 'config should be empty.');

		//check ignoring
		FacebookProxy::reset();
		$result = FacebookProxy::config(array('foo'));
		$this->assertTrue($result,array(),'config should return true');
		$this->assertIdentical(FacebookProxy::config(),array(),'config should be empty');

		//check ingoring vs. existing but unset associations
		FacebookProxy::reset();
		$result = FacebookProxy::config(array('appId'));
		$this->assertTrue($result,array(),'config should return true');
		$this->assertIdentical(FacebookProxy::config(),array(),'config should be empty');

		//check valid Settings
		FacebookProxy::reset();
		$sampleConfig = array('appId'=>'hello');
		$result = FacebookProxy::config($sampleConfig);
		$this->assertTrue($result,'config should return true');
		$this->assertIdentical(FacebookProxy::config(),$sampleConfig,'config should not be empty');

		//check vs. complete Settings
		FacebookProxy::reset();
		$result = FacebookProxy::config($this->_mockDefaults);
		$this->assertTrue($result,'config should return true');
		$this->assertIdentical(
			FacebookProxy::config(),$this->_mockDefaults,'config should not be empty'
		);
		
		Libraries::remove('li3_facebook');
		Libraries::add('li3_facebook',$oldConfig);
		//FaceBookProxy::foo();
		//die(print_r(array($result,FacebookProxy::config()),true));
	}

	public function testCheckConfiguration(){
		//invalid Settings
		$invalidSettings = array(
			array(
				'config' => array(),
				'exception' => 'Configuration: `appId` should be set'
			),
			array(
				'config' => array('appId' => 'foo'),
				'exception' => 'Configuration: `secret` should be set'
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'cookie' => true
				),
				'exception' => 'Configuration: `cookie` not yet supported'
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'domain' => true
				),
				'exception' => 'Configuration: `domain` not yet supported'
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'fileUpload' => true
				),
				'exception' => 'Configuration: `fileUpload` not yet supported'
			)
		);
		$validSettings = array(
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar'
				)
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'cookie' => false
				)
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'domain' => false
				)
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'fileUpload' => false
				)
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'domain' => false,
					'fileUpload' => false
				)
			),
			array(
				'config' => array(
					'appId' => 'foo',
					'secret' => 'bar',
					'cookie' => false,
					'domain' => false
				)
			)
		);

		$proxy = new FacebookProxy();
		foreach ($invalidSettings as $test){
			try {
				//$this->expectException($test['exception']);
				$proxy::checkConfiguration($test['config']);
				$this->assertFalse(true, $test['exception']);
			} catch (Exception $e){
				$this->assertIdentical($test['exception'], $e->getMessage(),$test['exception']);
			}
		}
		foreach ($validSettings as $test){
			$this->assertTrue($proxy::checkConfiguration($test['config']));
		}
	}

	public function testProxying(){
		//$this->skipIf(True);
		//$this->assertFalse(FacebookProxy::__init());
		$fb = new FacebookProxy();
		$fb::$_validateConfiguration = false;

		$this->assertIdentical('', $fb::run('getAppId'));

		$fb::reset();
		$expected = array(
			'appId' => 'foo',
			'secret' => 'bar'
			);
		$fb::config($expected);
		$this->assertIdentical($expected['appId'], $fb::run('getAppId'));
		$this->assertIdentical($expected['secret'], $fb::run('getApiSecret'));

		$expectedMessage = 'li3_facebook\extensions\FacebookProxy Method ' .
			'`unknownApiCall` is not callable';
		$this->expectException($expectedMessage);
		$this->assert($fb::run('unknownApiCall'));
	}
}

?>