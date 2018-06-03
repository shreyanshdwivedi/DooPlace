<?php

$config = parse_ini_file(dirname(dirname(dirname(__FILE__))).'/app-config.ini', true);
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

require_once 'Facebook\FacebookJavaScriptLoginHelper.php';
require_once 'Facebook\FacebookRedirectLoginHelper';
require_once 'Facebook\FacebookRequest';
require_once 'Facebook\FacebookResponse';
require_once 'Facebook\FacebookSDKException';
require_once 'Facebook\FacebookSession';
require_once 'Facebook\FacebookRequestException';
require_once 'Facebook\GraphObject'; 
require_once 'Facebook\GraphUser';

FacebookSession::setDefaultApplication($config['app_id'], $config['app_secret']);

$helper = new FacebookRedirectLoginHelper();
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
}

//show if the user is logged in or not
if ($session) {
  // Logged in
  echo ('User is logged in');
} else {
  echo ('User is not logged in');
}

?>