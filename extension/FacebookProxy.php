<?php

namespace li3_facebook\extension;

use lithium\core\Libraries;
//use lithium\core\Environment;

use Exception;
use lithium\core\ClassNotFoundException;

/**
* The `FacebookProxy` class handles all Facebook related functionalities.
* The class is mainly a lithium wrapper for the existing Facebook API. It is oriented by
* the proxy-pattern which is using the original FB-API as a singleton.
* It has to be configured by an api key and the secret.
*/
class FacebookProxy extends \lithium\core\StaticObject {

	/**
	 * Holds the configuration Options
	 * @var array
	*/
	protected static $_config = array();

	/**
	 * These are the class `defaults`
	 * @var array
	 */
	protected static $_defaults = array(
		'appId' => '',
		'secret' => '',
		'cookie' => false,
		'domain' => false,
		'fileUpload' => false
	);

	/**
	 * If true, class will automatically fetch data from libraries settings
	 * Set this to false if you want to do configuration manually or in debug mode
	 * @var boolean
	 */
	public static $_autoConfigure = true;

	/**
	 * If false, given Config wont be validated
	 * @var boolean
	 */
	public static $_validateConfiguration = true;

	/**
	 * Holds the Facebook Api Version Strings. associated to the
	 * tested git hash (just for debug info)
	 * @var array of Version Strings
	 */
	public static $__compatibleApiVersions = array(
		'2.1.2' => '04168d544f71293fab7622fa81161eef51db808e'
	);

	/**
	 * Holds the FacebookAPI as singleton
	 * @var Facebbok
	 */
	public static $_facebookApiInstance = null;

	public function __construct($config = array()) {
		if ($config){
			static::config($config);
		}
	}

	/**
	 *
	 * @return void
	 */
	public static function __init() {
		if (static::$_autoConfigure){
			$libraryConfig = Libraries::get('li3_facebook');
			static::config($libraryConfig + static::$_defaults);
		}

		/* wont work */
		/*
		 static::applyFilter('invokeMethod', function($self, $params, $chain) {
			// Custom pre-dispatch logic goes here
			die("awesome");
			$response = $chain->next($self, $params, $chain);
			return $response;
			});
		 *
		 */
	}

	/**
	 * Sets configurations for the FacebookApi.
	 * This Method is basically a copy and edit of the config in adaptable.
	 *
	 * @see lithium\core\adaptable
	 *
	 * @param array $config Configuratin.
	 * @return array|void `Collection` of configurations or true if setting configurations.
	 */
	public static function config($config = null) {
		//set if `config`is given
		if ($config && is_array($config)) {
			//filter only accepts configuration options
			foreach ($config as $key => $value){
				if (\array_key_exists($key, static::$_defaults)){
					static::$_config[$key] = $value;
				}
			};
			return true;
		}
		//if we r using more than one config...=> named config
		/*
		if ($config) {
			return static::_config($config);
		}
		 */

		//set and filter
		//$result = array();

		//due false and unset Values we disable the filtering:
		//static::$_config = array_filter(static::$_config);
		//we dont use named configs (now)
		/*
		foreach (array_keys(static::$_config) as $key) {
			$result[$key] = static::_config($key);
		}
		 */
		//so we return the current config
		$result = static::$_config;
		return $result;
	}

	/**
	 * Clears all configurations.
	 *
	 * @return void
	 */
	public static function reset() {
		static::$_facebookApiInstance = null;
		static::$_config = array();
	}

	/**
	 * Does proxying the method calls
	 * @param string $method
	 * @param mixed $arguments
	 */
	public static function __callStatic($method, $arguments) {
		return static::run($method,$arguments);
	}

	/**
	 * Calls should be rerouted to the facebookApiInstance of the proxy
	 * @todo insert a callable existance check
	 *
	 * @see lithium/core/StaticObject
	 *
	 * @throws FacebookApiException
	 *
	 * @param string $method
	 * @param mixed $params
	 * @return mixed Return value of the called api method
	 * @filter this Method is filterable
	 */
	public static function run($method, $params = array()) {
		$params = compact('method', 'params');
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			extract($params);

			if (!$self::$_facebookApiInstance){
				$self::invokeMethod('instanciateFacebookApi');
			}

			//@todo: insert callable existance check here!
			if (!\is_callable(array($self::$_facebookApiInstance,$method))){
				throw new Exception(__CLASS__ . " Method `$method` is not callable");
			}

			switch (count($params)) {
				case 0:
					return $self::$_facebookApiInstance->$method();
				case 1:
					return $self::$_facebookApiInstance->$method($params[0]);
				case 2:
					return $self::$_facebookApiInstance->$method($params[0], $params[1]);
				case 3:
					return $self::$_facebookApiInstance->$method(
						$params[0], $params[1], $params[2]
					);
				case 4:
					return $self::$_facebookApiInstance->$method(
						$params[0], $params[1], $params[2], $params[3]
					);
				case 5:
					return $self::$_facebookApiInstance->$method(
						$params[0], $params[1], $params[2], $params[3], $params[4]
					);
				default:
					//i am not sure if this is a good idea
					return call_user_func_array(array(get_called_class(), $method), $params);
			}
		});
	}

	/**
	 * Does savely instanciating the Facebook Api.
	 * @throws Exception for various Errors.
	 *
	 * @param array $config
	 * @return Facebook $apiInstance
	 * @filter This method may be filtered.
	 */
	public static function instanciateFacebookApi($config = array()){
		$params = compact('config');
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			extract($params);

			if ($self::$_validateConfiguration){
				$self::invokeMethod('checkConfiguration',$config);
			}
			if ($config){
				$self::config($config);
			}
			$self::invokeMethod('_checkApiAvailability');
			$self::invokeMethod('_requireFacebookApi');
			$self::invokeMethod('_checkApiCompatibility');
			$apiInstance = new \Facebook($self::config());
			if (!$apiInstance){
				throw new Exception('Facebook Api cant instanciated!');
			}
			$self::$_facebookApiInstance = $apiInstance;
			return $apiInstance;
		});
	}

	/**
	 * checks the configuration against Problems (and unsupported features)
	 *
	 * @todo finish this!
	 *
	 * @throws Exceptions if there are problems
	 *
	 * @param array $config
	 * @return boolean
	 * @filter This method may be filtered.
	 */
	public static function checkConfiguration($config = array()){
		$params = compact('config');
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			extract($params);

			if (!$config){
			$config = $self::invokeMethod('config');
			}
			if (empty($config['appId'])){
				throw new Exception('Configuration: `appId` should be set');
			}
			if (empty($config['secret'])){
				throw new Exception('Configuration: `secret` should be set');
			}
			if (!empty($config['cookie'])){
				throw new Exception('Configuration: `cookie` not yet supported');
			}
			if (!empty($config['domain'])){
				throw new Exception('Configuration: `domain` not yet supported');
			}
			if (!empty($config['fileUpload'])){
				throw new Exception('Configuration: `fileUpload` not yet supported');
			}
			return true;
		});
	}

	/**
	 * Fetches the ApiPath and checks if the Api is there
	 */
	protected static function _checkApiAvailability(){
		$fbLib = static::_getApiPath();
		if (!\file_exists($fbLib)){
			throw new ClassNotFoundException('Facebook Lib not there! Do git submoule init first!');
		}
	}

	/**
	 * constructs the Api Path by this file
	 * @return string full Path to the FacebookApi
	 * @filter This method may be filtered.
	 */
	protected static function _getApiPath(){
		$params = array();
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			extract($params);

			$currentPath = dirname(__FILE__);
			$fbLib = $currentPath . '/../libraries/facebook-sdk/src/facebook.php';
			return \realpath($fbLib);
		});
	}

	/**
	 * Requires the Facebok Api.
	 *
	 * @throws (rethrows) Exception if curl or json_decode not reachable!
	 * @return void
	 */
	protected static function _requireFacebookApi(){
		require_once static::_getApiPath();
	}

	/**
	 * Checks the ApiVersion against this Proxy capabilities
	 *
	 * @throws Exception if the Library is not compatible
	 * @return void
	 */
	protected static function _checkApiCompatibility(){
		$versions = static::$__compatibleApiVersions;
		if (!\array_key_exists(\Facebook::VERSION, $versions)){
			throw new Exception('Facebook Library is not compatible to our library');
		}
	}

	/**
	 * Returns the instaciated Facebook Class for own usage.
	 *
	 * @return Facebook $facebookInstance
	 */
	public static function getApiInstance(){
		return static::$_facebookApiInstance;
	}
}

?>