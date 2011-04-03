<?php
/*
 * this is the li3_Facebook Wrapper class
 */

namespace li3_facebook\extension;

use Exception;
use lithium\core\Libraries;

class FbWrapper extends \lithium\core\Object {
	
	/**
	 * Holds the Facebook Instance
	 * @var Facebook 
	 */
	protected $_facebookInstance = null;
	
	protected static $_instance = null;
	
	protected static $_librarySettings = null;

	protected static $_defaults = array(
		'appId' => '',
		'secret' => '',
		'cookie' => false,
		'domain' => false,
		'fileUpload' => false,
	);

	public static function __callStatic($method,$arguments){
		if(!static::$_instance){
			static::$_instance = static::_getInstance();
		}
	}
	
	public static function _getInstance(){
		static::checkRequirements();
		
		$config = Libraries::get('li3_facebook');
		static::$_librarySettings = $config;
		static::$_instance = new FbWrapper($config);
	}
	
	//doing WB Exception Wrapping...
	//check against FB API existance && API Version!

	//compatible FacebookApi..
	public static $__compatibleVersions = array(
		'2.1.2',
	);

	/**
	 * @return void
	 */
	protected function _init(){
		parent::_init();		
		$this->_config = $this->_config + static::$_defaults;
		$this->checkConfiguration();
		
		//$this->_facebookInstance = new \Facebook($config);
		
	}
	
	public static function checkRequirements(){
		static::_requireFacebookLibrary();
		static::_checkLibraryCompatibility();
	}

	/**
	 * @throws Exception Library not found
	 */
	protected static function _requireFacebookLibrary(){
		$currentPath = dirname(__FILE__);
		$fbLib = $currentPath.'/../libraries/facebook-sdk/src/facebook.php';
		$fbLib = \realpath($fbLib);
		if(!\file_exists($fbLib)){
		 throw new Exception('Facebook Lib not there! Do git submoule init first!');
		}
		require_once $fbLib;
	}
	
	protected static function _checkLibraryCompatibility(){
		$versions = static::$__compatibleVersions;
		if(!\in_array(\Facebook::VERSION, $versions)){
			throw new Exception('Facebook Library is not compatible to our library');
		}
	}
	
	public function checkConfiguration(){
		if(empty($this->_config['appId'])){
			throw new Exception('Configuration: `appId` should be set');
		}
		if(empty($this->_config['secret'])){
			throw new Exception('Configuration: `secret` should be set');
		}
		if(!empty($this->_config['cookie'])){
			throw new Exception('Configuration: `cookie` not yet suported');
		}
		if(!empty($this->_config['domain'])){
			throw new Exception('Configuration: `domain` not yet supported');
		}
		if(!empty($this->_config['fileUpload'])){
			throw new Exception('Configuration: `fileUpload` not yet supported');
		}
	}
	
	/*
	  public function __construct($config) {
    $this->setAppId($config['appId']);
    $this->setApiSecret($config['secret']);
    if (isset($config['cookie'])) {
      $this->setCookieSupport($config['cookie']);
    }
    if (isset($config['domain'])) {
      $this->setBaseDomain($config['domain']);
    }
    if (isset($config['fileUpload'])) {
      $this->setFileUploadSupport($config['fileUpload']);
    }
  }
	 */
}
?>
