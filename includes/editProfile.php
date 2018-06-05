<?php
    session_start();

    $conn = new mysqli("localhost", "root", "", "codingCampus");

    if(isset($_POST['editProfile'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $phoneNum = $_POST['phoneNum'];
        $language = $_POST['language'];
        $currency = $_POST['currency'];
        $location = $_POST['location'];
        $bio = $_POST['bio'];
        $school = $_POST['school'];
        $work = $_POST['work'];
        $timezone = $_POST['timezone'];

        if($_SESSION['loginType'] == "gmail") {
            $check = $_SESSION['etag'];
            $stmt = $conn->prepare("SELECT * FROM gmailUsers WHERE etag=?");
            $stmt->bind_param("s",$_SESSION['etag']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); 
            $stmt = $conn->prepare("UPDATE gmailUsers SET first_name=? AND last_name=? AND gender=? AND dob=? AND email=? AND phoneNum=? AND `language`=? AND currency=? AND `location`=? AND bio=? AND school=? AND work=? AND timezone=? WHERE etag=?");
        } else if($_SESSION['loginType'] == "email") {
            $table = "users";
            $check = $_SESSION['email'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
            $stmt->bind_param("s",$_SESSION['email']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); 
            echo('email');
            $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, gender=?, dob=?, email=?, phoneNum=?, `language`=?, currency=?, `location`=?, bio=?, school=?, work=?, timezone=? WHERE email=?");
        } else if($_SESSION['loginType'] == "facebook") {
            $table = "fbUsers";
            $check = $_SESSION['fb-id'];
            $stmt = $conn->prepare("SELECT * FROM fbUsers WHERE `uid`=?");
            $stmt->bind_param("s",$_SESSION['fb-id']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $stmt = $conn->prepare("UPDATE fbUsers SET first_name=? AND last_name=? AND gender=? AND dob=? AND email=? AND phoneNum=? AND `language`=? AND currency=? AND `location`=? AND bio=? AND school=? AND work=? AND timezone=? WHERE `uid`=?");
        } 

        $stmt->bind_param("ssssssssssssss", $first_name, $last_name, $gender, $dob, $email, $phoneNum, $language, $currency, $location, $bio, $school, $work, $timezone, $check);
        $result = $stmt->execute();
        $stmt->close();
        echo($_SESSION['email']);

        $_SESSION['success'] = "User details edited successfully";
        header('Location: ../profile.php?section=edit');
    }

    if(isset($_POST['imageEdit'])) {
        $target_dir = "../img/uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $filename = basename( $_FILES['image']['name']);
        $path_parts = pathinfo($_FILES["image"]["name"]);
        $image_path = $path_parts['filename'].'_'.date("Y-m-d_h:i:sa").'.'.$path_parts['extension'];
        $target_file = $target_dir.$image_path;
    
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $_SESSION["error"] = "File is not an image.";
            $uploadOk = 0;
        }
    
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION["error"] = "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }
    
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $_SESSION["success"] = "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                $img = $target_file;
            } else {
                $_SESSION["error"] = "Sorry, there was an error uploading your file.";
            }
        }
        if($_SESSION['loginType'] == "gmail") {
            $stmt = $conn->prepare("UPDATE gmailUsers SET `image`=? WHERE etag=?");
            $stmt->bind_param("ss", $img, $_SESSION['etag']);
            $result = $stmt->execute();
            $stmt->close();
        } else if($_SESSION['loginType'] == "email") {
            $stmt = $conn->prepare("UPDATE users SET `image`=? WHERE email=?");
            $stmt->bind_param("ss", $img, $_SESSION['email']);
            $result = $stmt->execute();
            $stmt->close();
        } else if($_SESSION['loginType'] == "facebook") {
            $stmt = $conn->prepare("UPDATE fbUsers SET `image`=? WHERE `uid`=?");
            $stmt->bind_param("ss", $img, $_SESSION['fb-id']);
            $result = $stmt->execute();
            $stmt->close();
        }
    }
?>