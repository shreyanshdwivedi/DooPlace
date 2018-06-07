<?php
    session_start();
?>    
    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php include 'includes/header.php'; ?>

    <?php include 'includes/navbar.php';?>
    <br>
    <?php include 'includes/slider.php'; ?>
    <br>
    <?php include 'includes/card.php'; ?>

    <?php include 'includes/footer.php'; ?>