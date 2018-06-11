<?php
    if (isset($_SESSION['images'])) {
        $success = 0;
        $type = 0;
        $j = 0;     // Variable for indexing uploaded image.
        $target_path = "../img/uploads/";     // Declaring Path for uploaded images.
        
        for ($i = 0; $i < count($_SESSION['images']['name']); $i++) {
            echo("I'm in for");
            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
            $ext = explode('.', basename($_SESSION['images']['name'][$i]));   // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.
            $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];     // Set the target path with a new name of image.
            $j = $j + 1;      // Increment the number of uploaded images according to the files in array.
            
            if (in_array($file_extension, $validextensions)) {
                echo("I'm in if one");
                if (move_uploaded_file($_SESSION['images']['tmp_name'][$i], $target_path)) {
                    // If file moved to uploads folder.
                    $stmt = $conn->prepare("INSERT INTO images(propertyID, `location`)
                    VALUES(?, ?)");
                    $stmt->bind_param("ss",$propertyID, $target_path);
                    $result = $stmt->execute();
                    $stmt->close();
                    echo("I'm in if 2");
                    $success += 1;
                }
            } else {     //   If File Size And File Type Was Incorrect.
                $type += 1;
                echo("I'm in else");
            }
        }
        if($success == $j) {
            $_SESSION['success'] = "Images uploaded successfully";
        } else if($type > 0) {
            $_SESSION['error'] = "Invalide file type";
        } else {
            $_SESSION['error'] = "Please try again!";
        }
    }
?>