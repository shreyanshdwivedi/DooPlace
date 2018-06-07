<?php
   // added in v4.0.0
   require_once dirname(dirname(__FILE__)).'/vendor/facebook/graph-sdk/src/Facebook/autoload.php';
   
   use Facebook\FacebookRequest;
   use Facebook\GraphObject;
   use Facebook\FacebookRequestException;
    
   // init app with app id and secret
//   FacebookSession::setDefaultApplication( '496544657159182','e6d239655aeb3e496e52fabeaf1b1f93' );
   $fb = new Facebook\Facebook([
        'app_id' => "215485965632061",
        'app_secret' => "8e81009fa3547ab653642bbb0948f617",
        'default_graph_version' => 'v2.2',
    ]);
    
    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://dooplacecampus.000webhostapp.com/includes/fb-callback.php', $permissions);
?>