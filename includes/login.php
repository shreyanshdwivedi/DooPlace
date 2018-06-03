<?php
// session_start();
// $config = parse_ini_file('../app-config.ini', true);
// require_once 'vendor/autoload.php';
// $fb = new Facebook\Facebook([
//   'app_id' => $config['app_id'], // Replace {app-id} with your app id
//   'app_secret' => $config['app_secret'],
//   'default_graph_version' => 'v2.2',
//   ]);

// $helper = $fb->getRedirectLoginHelper();

// $permissions = ['email', 'user_gender']; // Optional permissions
// $loginUrl = $helper->getLoginUrl('http://localhost/desktop/Website-CodingCampus/includes/fb-callback.php', $permissions);

session_start();
 
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
 
// init app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication('APPID','SECRET');
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://www.metah.ch/' );
 
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
 
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
   
  // print data
  echo  print_r( $graphObject, 1 );
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}

?>