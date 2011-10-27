<?php
/**
 * li3_facebook helper, mainly, to embed and use the Facebook JavaScript SDK.
 * However, it also provides some other methods that relate to the PHP SDK.
 *
*/
namespace li3_facebook\extensions\helper;

use lithium\storage\Session;
use lithium\core\Libraries;
use li3_facebook\extensions\FacebookProxy;
use lithium\core\Environment;
use lithium\net\http\Router;

class Facebook extends \lithium\template\helper\Html {

	protected $_locales = array(
		'af_ZA',
		'sq_AL',
		'ar_AR',
		'hy_AM',
		'ay_BO',
		'az_AZ',
		'eu_ES',
		'be_BY',
		'bn_IN',
		'bs_BA',
		'bg_BG',
		'ca_ES',
		'ck_US',
		'hr_HR',
		'cs_CZ',
		'da_DK',
		'nl_NL',
		'nl_BE',
		'en_PI',
		'en_GB',
		'en_UD',
		'en_US',
		'eo_EO',
		'et_EE',
		'fo_FO',
		'tl_PH',
		'fi_FI',
		'fb_FI',
		'fr_CA',
		'fr_FR',
		'gl_ES',
		'ka_GE',
		'de_DE',
		'el_GR',
		'gn_PY',
		'gu_IN',
		'he_IL',
		'hi_IN',
		'hu_HU',
		'is_IS',
		'id_ID',
		'ga_IE',
		'it_IT',
		'ja_JP',
		'jv_ID',
		'kn_IN',
		'kk_KZ',
		'km_KH',
		'tl_ST',
		'ko_KR',
		'ku_TR',
		'la_VA',
		'lv_LV',
		'fb_LT',
		'li_NL',
		'lt_LT',
		'mk_MK',
		'mg_MG',
		'ms_MY',
		'ml_IN',
		'mt_MT',
		'mr_IN',
		'mn_MN',
		'ne_NP',
		'se_NO',
		'nb_NO',
		'nn_NO',
		'ps_AF',
		'fa_IR',
		'pl_PL',
		'pt_BR',
		'pt_PT',
		'pa_IN',
		'qu_PE',
		'ro_RO',
		'rm_CH',
		'ru_RU',
		'sa_IN',
		'sr_RS',
		'zh_CN',
		'sk_SK',
		'sl_SI',
		'so_SO',
		'es_LA',
		'es_CL',
		'es_CO',
		'es_MX',
		'es_ES',
		'es_VE',
		'sw_KE',
		'sv_SE',
		'sy_SY',
		'tg_TJ',
		'ta_IN',
		'tt_RU',
		'te_IN',
		'th_TH',
		'zh_HK',
		'zh_TW',
		'tr_TR',
		'uk_UA',
		'ur_PK',
		'uz_UZ',
		'vi_VN',
		'cy_GB',
		'xh_ZA',
		'yi_DE',
		'zu_ZA'
	);

	protected $_preferredLocale = array(
		'af' => 'af_ZA',
		'sq' => 'sq_AL',
		'ar' => 'ar_AR',
		'hy' => 'hy_AM',
		'ay' => 'ay_BO',
		'az' => 'az_AZ',
		'eu' => 'eu_ES',
		'be' => 'be_BY',
		'bn' => 'bn_IN',
		'bs' => 'bs_BA',
		'bg' => 'bg_BG',
		'ca' => 'ca_ES',
		'ck' => 'ck_US',
		'hr' => 'hr_HR',
		'cs' => 'cs_CZ',
		'da' => 'da_DK',
		'nl' => 'nl_NL',
		'en' => 'en_US',
		'eo' => 'eo_EO',
		'et' => 'et_EE',
		'fo' => 'fo_FO',
		'tl' => 'tl_PH',
		'fi' => 'fi_FI',
		'fb' => 'fb_FI',
		'fr' => 'fr_FR',
		'gl' => 'gl_ES',
		'ka' => 'ka_GE',
		'de' => 'de_DE',
		'el' => 'el_GR',
		'gn' => 'gn_PY',
		'gu' => 'gu_IN',
		'he' => 'he_IL',
		'hi' => 'hi_IN',
		'hu' => 'hu_HU',
		'is' => 'is_IS',
		'id' => 'id_ID',
		'ga' => 'ga_IE',
		'it' => 'it_IT',
		'ja' => 'ja_JP',
		'jv' => 'jv_ID',
		'kn' => 'kn_IN',
		'kk' => 'kk_KZ',
		'km' => 'km_KH',
		'tl' => 'tl_ST',
		'ko' => 'ko_KR',
		'ku' => 'ku_TR',
		'la' => 'la_VA',
		'lv' => 'lv_LV',
		'fb' => 'fb_LT',
		'li' => 'li_NL',
		'lt' => 'lt_LT',
		'mk' => 'mk_MK',
		'mg' => 'mg_MG',
		'ms' => 'ms_MY',
		'ml' => 'ml_IN',
		'mt' => 'mt_MT',
		'mr' => 'mr_IN',
		'mn' => 'mn_MN',
		'ne' => 'ne_NP',
		'se' => 'se_NO',
		'nb' => 'nb_NO',
		'nn' => 'nn_NO',
		'ps' => 'ps_AF',
		'fa' => 'fa_IR',
		'pl' => 'pl_PL',
		'pt' => 'pt_PT',
		'pa' => 'pa_IN',
		'qu' => 'qu_PE',
		'ro' => 'ro_RO',
		'rm' => 'rm_CH',
		'ru' => 'ru_RU',
		'sa' => 'sa_IN',
		'sr' => 'sr_RS',
		'zh' => 'zh_CN',
		'sk' => 'sk_SK',
		'sl' => 'sl_SI',
		'so' => 'so_SO',
		'es' => 'es_ES',
		'sw' => 'sw_KE',
		'sv' => 'sv_SE',
		'sy' => 'sy_SY',
		'tg' => 'tg_TJ',
		'ta' => 'ta_IN',
		'tt' => 'tt_RU',
		'te' => 'te_IN',
		'tr' => 'tr_TR',
		'uk' => 'uk_UA',
		'ur' => 'ur_PK',
		'uz' => 'uz_UZ',
		'vi' => 'vi_VN',
		'cy' => 'cy_GB',
		'xh' => 'xh_ZA',
		'yi' => 'yi_DE',
		'zu' => 'zu_ZA'
	);

	protected $_locale;
    
	public function _init() {
		parent::_init();

		// Get some required values
		$facebook_config = Libraries::get('li3_facebook');
		if(!empty($facebook_config)) {
			extract($facebook_config);
		}

		$this->_appId = (isset($appId)) ? $appId : false;
		$locale = Environment::get('locale');
		switch (true) {
			case ($locale && in_array($locale, $this->_locales)):
				$this->_locale = $locale;
				break;
			case ($locale && isset($this->_preferredLocale[$locale])):
				$this->_locale = $this->_preferredLocale[$locale];
				break;
			default:
				$this->_locale = 'en_US';
		}
	}

	/**
	 * Displays a basic Facebook Connect login button.
	 * Works with the PHP SDK to get the login URL.
	 * This does not use the JavaScript SDK for xfbml.
	 *
	 * @param $options Array
	 * @return String
	*/
	public function login(array $options = array()) {
		$view = $this->_context->view();
		$user = FacebookProxy::getUser();
		$loginUrl = false;
		$domain = $this->_context->request();
		if (!$user) {
			$loginUrl = FacebookProxy::getLoginUrl(array(
				'scope' => 'email',
				'redirect_uri' => 'http://local.soundaymusic.com/en/login'
			));
		}
		else {
			$logoutUrl = FacebookProxy::getLogoutUrl(array('next' => 'http://local.soundaymusic.com/en/logout' ));
		}
		return $view->render(
			array('element'=>'login'), 
			compact('loginUrl', 'logoutUrl'),
			array('library' => 'li3_facebook')
		);
	}

	/**
	 * Embeds the Facebook JavaScript SDK
	 * Facebook app id, locale, etc. is set in app/bootstrap/libraries.php
	 * with configuration options for Libraries::add('minerva').
	 * ex.
	 * Libraries::add('minerva', array(
	 *     'facebook' => array(
	 *         'appId' => 0000,
	 *         'secret' => 0000,
	 *         'locale' => 'en_US'
	 *     )
	 * ))
	 *
	 * TODO: add other options to be passed... like "status", "cookie" and "xfbml"
	 *
	 * @param $async Boolean Whether or not to embed it so it loads asynchronously
	 * @param $debug Boolean Whether or not to use the debug version
	 * @return String The HTML embed code
	*/
	public function facebook_init($async=true, $debug=false) {

		$script = 'all.js';
		if($debug === true) {
			$script = 'core.debug.js';
		}

		$appId = $this->_appId;
		$locale = $this->_locale;

		if($appId) {
			$view = $this->_context->view();
			return$view->render(
				array('element'=>'init'), 
				compact('appId', 'locale', 'script', 'async'),
				array('library' => 'li3_facebook')
			);
		}
		return null;
	}
}

?>