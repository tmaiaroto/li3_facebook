<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_facebook\tests\cases\extensions\adapter\security\auth;

use li3_facebook\tests\mocks\extensions\adapter\security\auth\MockFacebook as Facebook;
use li3_facebook\extensions\FacebookProxy;
use lithium\action\Request;
use lithium\core\Libraries;
use lithium\core\ConfigException;

class FacebookTest extends \lithium\test\Unit {
	
	/**
	 * @var Request $request Object
	 */
	public $request;
	
	public function setUp() {
		$this->request = new Request();
	}

	public function tearDown() {}

	//test an empty facebook config => should throw an Exception
	public function testEmptyConfig(){
		$oldConfig = Libraries::get('li3_facebook');
		$subject = new Facebook();
		//disable validation inside the proxy
		FacebookProxy::$_validateConfiguration = false;
		
		Libraries::remove('li3_facebook');
		Libraries::add('li3_facebook');
		
		$this->expectException('Configuration: `appId` should be set');
		$this->assertTrue($subject->check($this->request));
		
		Libraries::remove('li3_facebook');
		Libraries::add('li3_facebook',$oldConfig);
	}
}
?>
