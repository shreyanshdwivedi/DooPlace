<?php

    $email = $_GET['email'];
    $key = $_GET['key'];
    $type = $_GET['type'];

    $conn = new mysqli("localhost", "root", "", "codingCampus");

    $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=?, email=?, paramValue=?");
    $stmt->bind_param("sss",$type, $email, $key);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($user == null) {
        $_SESSION['error'] = "Please enter valid URL";
        $_SESSION['isLoggedIn'] = false;
        header('Location: index.php');
    } else {
        $stmt = $conn->prepare("UPDATE users SET email_verified=TRUE WHERE `type`=?, email=?, paramValue=?");
        $stmt->bind_param("sss",$type, $email, $key);
        $result = $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = "Email successfully verified";
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['first_name']." ".$user['last_name'];
        $_SESSION['isLoggedIn'] = true;
        header("Location: index.php");
    }
?>