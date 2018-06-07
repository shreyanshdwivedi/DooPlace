<?php
ob_start();
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
        
        $etag = $user_info['etag'];
        $name = explode(" ", $user_info["displayName"]);
        
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT id from users WHERE paramValue = ?");
        $stmt->bind_param("s", $etag);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        $type = "gmail";
        $param = "etag";
        
        if(!($num_rows > 0)){
            $stmt = $conn->prepare("INSERT INTO users(`type`, `param`, paramValue, `first_name`, `last_name`, `email`, `language`, `image`) 
                        values(?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $type, $param, $user_info['etag'], $name[0], $name[1], $user_info['emails'][0]["value"], strtoupper($user_info['language']), $user_info['image']['url']);
            $result = $stmt->execute();
            $stmt->close();
        }

            $type = "gmail";
            $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=?, paramValue=?");
            $stmt->bind_param("ss", $type, $etag);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        
            $_SESSION['access-token'] = (string)$access_token;
            $_SESSION['success'] = "You are successfully logged in";
            $_SESSION['name'] = $user_info["displayName"];
            $_SESSION['image'] = $user_info['image']['url']; 
            $_SESSION['userID'] = $user['id'];
            $_SESSION['loginType'] = "gmail";
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['paramValue'] = $user_info['etag'];

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