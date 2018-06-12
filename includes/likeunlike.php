<?php
session_start();
if(isset($_POST)) {

$propertyID = $_POST['propertyID'];
$conn = new mysqli("localhost", "root", "", "codingCampus");
$userID = $_SESSION['userID'];

// Check entry within table
$query = "SELECT * FROM likes WHERE userID=".$userID." AND propertyID=".$propertyID;
if($stm = $conn->prepare($query)){
    $stm->execute();
    $stm->store_result();
    $num = $stm->num_rows;
    $stm->close();
}

    if($num == 0){
        $stmt = $conn->prepare("INSERT INTO likes(userID, propertyID)
            VALUES(?, ?)");
        $stmt->bind_param("ss", $userID, $propertyID);
        $stmt->execute();
        $stmt->close();
        echo 1;
    }else {
        $stmt = $conn->prepare("DELETE FROM likes WHERE userID=? AND propertyID=?");
        $stmt->bind_param("ss",$propertyID, $userID);
        $stmt->execute();
        $stmt->close();
        echo 0;
    }
} else {
    return -1;
}

?>