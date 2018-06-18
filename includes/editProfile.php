<?php
    session_start();

    $conn = new mysqli("localhost", "root", "", "codingCampus");

    if(isset($_POST['editProfile'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $countryCode = $_POST['countryCode'];
        $phoneNum = $_POST['phoneNum'];
        $language = $_POST['language'];
        $currency = $_POST['currency'];
        $location = $_POST['location'];
        $bio = $_POST['bio'];
        $school = $_POST['school'];
        $work = $_POST['work'];
        $timezone = $_POST['timezone'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=? AND paramValue=?");
        $stmt->bind_param("ss", $_SESSION['loginType'], $_SESSION['paramValue']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, gender=?, dob=?, email=?, countryCode=?, phoneNum=?, `language`=?, currency=?, `location`=?, bio=?, school=?, work=?, timezone=? WHERE `type`=? AND paramValue=?");
        $stmt->bind_param("ssssssssssssssss", $first_name, $last_name, $gender, $dob, $email, $countryCode, $phoneNum, $language, $currency, $location, $bio, $school, $work, $timezone, $_SESSION['loginType'], $_SESSION['paramValue']);
        $result = $stmt->execute();
        $stmt->close();

        if($_SESSION['loginType'] == "email") {
            $_SESSION['paramValue'] = $email;
        }
        $_SESSION['name'] = $first_name." ".$last_name;
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

        $stmt = $conn->prepare("UPDATE users SET `image`=? WHERE `type`=? AND paramValue=?");
        $stmt->bind_param("sss", $img, $_SESSION['loginType'], $_SESSION['paramValue']);
        $result = $stmt->execute();
        $stmt->close();
        header("Location: ../profile.php?section=image");
    }
?>