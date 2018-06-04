<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '1017270586778-2574lof1r6nv1ot3827qid3l2g5kkvgf.apps.googleusercontent.com';
$clientSecret = 'qjOKvNg2HccdhP_1EDiJC5Db';
$redirectURL = 'https://dooplacecampus.000webhostapp.com/includes/gmail-callback.php';

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to Dooplace');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>