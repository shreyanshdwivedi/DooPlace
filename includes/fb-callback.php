<?php

    if(!session_id()) {
        session_start();
    }
    require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
    $fb = new Facebook\Facebook([
        'app_id' => "",
        'app_secret' => "",
        'default_graph_version' => 'v2.2',
    ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
// echo '<h3>Access Token</h3>';
// var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
// echo '<h3>Metadata</h3>';
// var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(''); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

if (isset($accessToken)) {
        // Logged in!
    // Now you can redirect to another page and use the
        // access token from $_SESSION['facebook_access_token'] 
        // But we shall we the same page
    // Sets the default fallback access token so 
    // we don't have to pass it to each request
    $fb->setDefaultAccessToken($accessToken);
    try {
        $response = $fb->get('/me?fields=email,name');
        $userNode = $response->getGraphUser();    
    }catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $username = $userNode->getName();
    $id = $userNode->getProperty('id');
    $email = $userNode->getProperty('email');
    $image = 'https://graph.facebook.com/'.$userNode->getId().'/picture?width=200';

    $conn = new mysqli("localhost", "id6063824_root", "Suraj@01", "id6063824_dooplace");

    $stmt = $conn->prepare("SELECT * FROM `fbUsers` WHERE id=?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->store_result();
    $num_rows = $stmt->num_rows;
    $stmt->close();
    if(!$num_rows) {
      $stmt = $conn->prepare("INSERT INTO fbUsers(`uid`, `name`, `email`, `image`) 
            values(?, ?, ?, ?)");
      $stmt->bind_param("ssss", $id, $username, $email, $image);
      $result = $stmt->execute();
      $stmt->close();
    }

        $_SESSION['success'] = "Logged In successfully";
        $_SESSION['fb-id'] = $id;
        $_SESSION['name'] = $username;
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['loginType'] = "facebook";
        header("Location: ../index.php");   
    
    }else{
      $permissions  = ['email'];
      $loginUrl = $helper->getLoginUrl($redirect,$permissions);
      echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }

?>