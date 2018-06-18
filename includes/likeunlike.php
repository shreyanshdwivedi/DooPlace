<?php
session_start();
if(isset($_POST)) {

$relatedID = $_POST['relatedID'];
$conn = new mysqli("localhost", "root", "", "codingCampus");
$userID = $_SESSION['userID'];

if($_POST['relatedTo'] == "property") {
    $relatedTo = 'property';
    $query = "SELECT * FROM likes WHERE userID=".$userID." AND (relatedID=".$relatedID." AND relatedTo='property')";

    if($stm = $conn->prepare($query)){
        $stm->execute();
        $stm->store_result();
        $num = $stm->num_rows;
        $stm->close();

        if($num == 0){
            $stmt = $conn->prepare("INSERT INTO likes(userID, relatedTo, relatedID)
                VALUES(?, ?, ?)");
            $stmt->bind_param("sss", $userID, $relatedTo, $relatedID);
            $stmt->execute();
            $stmt->close();
            echo 1;
        }else {
            $sql = "DELETE FROM likes WHERE userID=".$userID." AND (relatedID=".$relatedID." AND relatedTo='property')";
            if($conn->query($sql) === TRUE) {
                echo 0;
            }
        }
    } else {
        echo -1;
    }
} else if($_POST['relatedTo'] == "restaurant") {
    $relatedTo = 'restaurant';
    $query = "SELECT * FROM likes WHERE userID=".$userID." AND (relatedID=".$relatedID." AND relatedTo='restaurant')";

    if($stm = $conn->prepare($query)){
        $stm->execute();
        $stm->store_result();
        $num = $stm->num_rows;
        $stm->close();

        if($num == 0){
            $stmt = $conn->prepare("INSERT INTO likes(userID, relatedTo, relatedID)
                VALUES(?, ?, ?)");
            $stmt->bind_param("sss", $userID, $relatedTo, $relatedID);
            $stmt->execute();
            $stmt->close();
            echo 1;
        }else {
            $sql = "DELETE FROM likes WHERE userID=".$userID." AND (relatedID=".$relatedID." AND relatedTo='restaurant')";
            if($conn->query($sql) === TRUE) {
                echo 0;
            }
        }
    } else {
        echo -1;
    }
}
$conn->close();
}

?>