<?php
    session_start();
    if(isset($_POST['loginBtn'])) {

        $email = $_POST['email'];
        $pass = $_POST['password'];
        $password = md5($pass);

        $conn = new mysqli("localhost", "root", "", "codingCampus");

        //Returning 0 means student created successfully
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND `password`=?");
        $stmt->bind_param("ss",$email,$password);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        if($user == null) {
            $_SESSION['error'] = "Please enter valid credentials!!";
            $_SESSION['isLoggedIn'] = false;
            header("Location: ../index.php");
        } else {
            $_SESSION['success'] = "Logged In successfully";
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['first_name']." ".$user['last_name'];
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['loginType'] = "email";
            header("Location: ../index.php");
        }
    } else {
        $_SESSION['error'] = "Something went wrong!!";
        $_SESSION['isLoggedIn'] = false;
        header("Location: ../index.php");
    }
?>