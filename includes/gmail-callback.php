<?php
session_start();

// Holds the Google application Client Id, Client Secret and Redirect Url
require_once('gmail-setting.php');

// Holds the various APIs involved as a PHP class. Download this class at the end of the tutorial
require_once('google-login-api.php');

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$gapi = new GoogleLoginApi();
		
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

		// Access Tokem
		$access_token = $data['access_token'];
		
		// Get user information
		$user_info = $gapi->GetUserProfileInfo($access_token);

		$_SESSION['success'] = "You are successfully logged in";
        $_SESSION['email'] = $user_info['emails'][0]["value"];
        $_SESSION['name'] = $user_info["displayName"];
        $_SESSION['image'] = $user_info['image']['url']; 
        $_SESSION['loginType'] = "gmail";
        $_SESSION['isLoggedIn'] = true;
        header("Location: ../index.php");

		// You may now want to redirect the user to the home page of your website
		// header('Location: home.php');
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}

?>