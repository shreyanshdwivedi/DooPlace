<?php
    session_start();
    if($_SESSION['loginType'] == "gmail") {
        include_once 'gpConfig.php';
        unset($_SESSION['token']);
        unset($_SESSION['userData']);
        $gClient->revokeToken();
    }
    unset($_SESSION['email']);
    unset($_SESSION['name']);
    session_destroy();
    $_SESSION['isLoggedIn'] = false;
    $_SESSION['success'] = "You have been successfully logged out!";
    header("Location: ../index.php");
?>