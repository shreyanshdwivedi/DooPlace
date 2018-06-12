<?php
    session_start();
    if(isset($_POST['details'])) {
   
        $amenity=$_SESSION['amenity'];  
        $amenities="";  
        foreach($amenity as $a)  
        {  
            $amenities .= $a.",";  
        }  
        session_unset($_SESSION['amenity']);

        $safety=$_SESSION['safetyAmenity'];  
        $safetyAmenities="";  
        foreach($safety as $s)  
        {  
            $safetyAmenities .= $s.",";  
        }  
        session_unset($_SESSION['safetyAmenity']);

        $space=$_SESSION['space'];  
        $spaces="";  
        foreach($space as $sp)  
        {  
            $spaces .= $sp.",";  
        }  
        session_unset($_SESSION['space']);

        //Property
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("INSERT INTO property(userID, `name`, propertyType, roomType, numGuests, numBedrooms, numBeds, numBathrooms, amenities, safetyAmenities, spaces, perHourRate, perDayRate)
         VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $_SESSION['userID'], $_POST['placeName'], $_SESSION['propertyType'], $_SESSION['roomType'], $_SESSION['numGuests'], $_SESSION['numBedrooms'], $_SESSION['numBeds'], $_SESSION['numBathrooms'], $amenities, $safetyAmenities, $spaces, $_POST['perHourRate'], $_POST['perDayRate']);
        $result = $stmt->execute();
        $stmt->close();
        
        $propertyID = $conn->insert_id;
        $_SESSION['propertyID'] = $propertyID;
        session_unset($_SESSION['propertyType']);
        session_unset($_SESSION['roomType']);
        session_unset($_SESSION['numGuests']);
        session_unset($_SESSION['numBedrooms']);
        session_unset($_SESSION['numBeds']);
        session_unset($_SESSION['numBathrooms']);

        //Location
        $stmt = $conn->prepare("INSERT INTO `location`(propertyID, suite, street, city, `state`, country, zip)
         VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss",$propertyID, $_SESSION['suite'], $_SESSION['street'], $_SESSION['city'], $_SESSION['state'], $_SESSION['country'], $_SESSION['zip']);
        $result = $stmt->execute();
        $stmt->close();

        session_unset($_SESSION['suite']);
        session_unset($_SESSION['street']);
        session_unset($_SESSION['city']);
        session_unset($_SESSION['state']);
        session_unset($_SESSION['country']);
        session_unset($_SESSION['zip']);

        //Images
        include 'uploadImages.php';
        session_unset($_SESSION['images']);

        $summary = $_POST['summary'];
        $about = $_POST['aboutPlace'];
        $access = $_POST['access'];
        $interaction = $_POST['interaction'];
        $note = $_POST['note'];
        $neighborhood = $_POST['neighborhood'];
        $getAround = $_POST['getAround'];

        //Location
        $stmt = $conn->prepare("INSERT INTO `description`(propertyID, summary, aboutPlace, access, interaction, note, neighborhood, getAround)
         VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss",$propertyID, $summary, $about, $access, $interaction, $note, $neighborhood, $getAround);
        $result = $stmt->execute();
        $stmt->close();

        header("Location: ../index.php");
    }
?>