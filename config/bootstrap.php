<?php

use \lithium\storage\Session;
use \lithium\security\Auth;
use \lithium\action\Dispatcher;
use \lithium\action\Response;
use \facebook\Facebook;
use \facebook\FacebookApiException;
use \app\models\User;

Session::config(array(
	//'default' => array('adapter' => 'Php'),
	'default' => array('adapter' => 'Model', 'model' => 'Session'),
));

Dispatcher::applyFilter('run', function($self, $params, $chain) {
	
		
	// Create our Application instance (replace this with your appId and secret).
	// TODO: make an adapter or some other sort of class that gets this injected. because this is redefined elewhere
	$facebook = new Facebook(array(
	  'appId'  => '1111111',
	  'secret' => '1111111',
	  'cookie' => true,
	));
	
	// We may or may not have this data based on a $_GET or $_COOKIE based session.
	//
	// If we get a session here, it means we found a correctly signed session using
	// the Application Secret only Facebook and the Application know. We dont know
	// if it is still valid until we make an API call using the session. A session
	// can become invalid if it has already expired (should not be getting the
	// session back in this case) or if the user logged out of Facebook.
	$session = $facebook->getSession();
	
	$me = null;
	// Session based API call.
	if ($session) {
            // Set the session
            Session::write('fb_session', $session);
            try {
                $uid = $facebook->getUser();
                $me = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
            }
	}
	
	// login or logout url will be needed depending on current user state.
	if (!empty($me)) {
	  // var_dump($facebook->getLogoutUrl());
	  // Set the logout URL too, pass the /users/logout url to the Facebook class method so it returns us to the proper place in order for the local session be to be destroyed too
	  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	  Session::write('fb_logout_url', $facebook->getLogoutUrl(array('next' => $protocol . $_SERVER['HTTP_HOST'] . '/users/logout')));
	  
        $now = date('Y-m-d h:i:s');
        // If logged in via Facebook Connect, see if the user exists in the local DB, if not, save it.
		$user = User::find('first', array('conditions' => array('facebook_uid' => $me['id'])));
			
		if(!$user) {
			// Save the new user
			$user = User::create();
			$user->save(array(
				'facebook_uid' => $me['id'],
				'first_name' => $me['first_name'],
				'last_name' => $me['last_name'],
				'confirmed' => true,
				//'banned' => false,
				'active' => true,
				//'url' => Util::unique_url(array('url' => \lithium\util\Inflector::slug('fb-'.$me['name']), 'model' => '\app\models\User')),
				'created' => $now,
				'modified' => $now,
				'last_login_time' => $now,
				'last_login_ip' => $_SERVER['REMOTE_ADDR'],
				'email' => null,
				'password' => null
			), array('validate' => false));
		} else {
			// Update the login time and IP if user exists, also update first and last name while we're at it if their FB name changed
			$user_data = $user->data();
			$user_data['last_login_time'] = $now;
			$user_data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
			$user_data['first_name'] = $me['first_name'];
			$user_data['last_name'] = $me['last_name'];
			$user_data['role'] = 'registered_facebook_user';
			//$user_data['friends.facebook'] = 
			// Facebook changes their shit too often. The 'username' key was available, but now it's not. There is a 'link' key that has what we need also so use that. I'm not sure why they removed 'username', it's supposed to be public information and if 'link' is still there, then why not 'username' ?? Who knows, Facebook is retarded.
			if(isset($me['username'])) {
				$user_data['profile_pics'][0] = array('primary' => true, 'url' => 'http://graph.facebook.com/'.$me['username'].'/picture?type=square');
			} elseif(isset($me['link'])){
				$user_data['profile_pics'][0] = array('primary' => true, 'url' => 'http://graph.facebook.com/'.substr($me['link'], 24).'/picture?type=square');
			}
			$user->save($user_data);
		}
		
		// Manually set the Auth
		Auth::set('user', $user->data());
		
	} else {
	  //var_dump($facebook->getLoginUrl());
	  // Set the login URL, even though the JS SDK can do it, it won't reload the page after logging in, which we want for our PHP stuff to update.
	  // The downside to not doing it with JS is that it will redirect instead of just showing a popup window...also show weird session stuff in the url bar
	  // TODO: see if we can detect the popup window closing or the login action somehow in the JS, to refresh the page with JS. Can't just look for a session, that'd create an infinite redirect loop
	  Session::write('fb_login_url', $facebook->getLoginUrl());
        
		$user = Auth::check('user');
		// If they aren't logged in and don't have a FB session, clear any local session we may have set
		// Or if they do have a local session but their user record has a facebook_uid (meaning once you FB connect, you must always login via FB connect)
		if((!$user) ||
		   (($user) && (isset($user['facebook_uid'])) && (!empty($user['facebook_uid'])) && (!$session))
		   ) {
			Auth::clear('user');
		}
		
	}
	
	//var_dump($me);
        
	return $chain->next($self, $params, $chain);
});
?>
