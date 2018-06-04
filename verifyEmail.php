<?php

    $email = $_GET['email'];
    $apikey = $_GET['apikey'];

    $conn = new mysqli("localhost", "root", "", "codingCampus");

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND apikey=?");
    $stmt->bind_param("ss",$email, $apikey);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($user == null) {
        $_SESSION['error'] = "Please enter valid URL";
        $_SESSION['isLoggedIn'] = false;
        header('Location: index.php');
    } else {
        $stmt = $conn->prepare("UPDATE users SET verified=TRUE WHERE email = ?");
        $stmt->bind_param("s",$email);
        $result = $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = "Email successfully verified";
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['first_name']." ".$user['last_name'];
        $_SESSION['isLoggedIn'] = true;
        header("Location: index.php");
    }
?>