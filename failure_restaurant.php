<?php
  /*
   *  @author   Gopal Joshi
   *  @about    PayUMoney Payment Gateway integration in PHP
   */

  $status      = $_POST["status"];
  $firstname   = $_POST["firstname"];
  $amount      = $_POST["amount"];
  $txnid       = $_POST["txnid"];
  $posted_hash = $_POST["hash"];
  $key         = $_POST["key"];
  $productinfo = $_POST["productinfo"];
  $email       = $_POST["email"];
  $salt        = "eCwWELxi"; // Your salt

  If(isset($_POST["additionalCharges"])) {

    $additionalCharges = $_POST["additionalCharges"];
    $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;      
  } else {	  
    $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
  }

  $hash = hash("sha512", $retHashSeq);

  if ($hash != $posted_hash) {
    include 'includes/header.php';
    include 'includes/navbar.php';

    echo "<center style='color: #333;'>";
    echo "Invalid Transaction. Please try again";
    echo "</center>";

    include 'includes/footer.php';

  } else {
    $bookingStatus = "failure";

    $conn = new mysqli("localhost", "root", "", "codingCampus");

    $stmt = $conn->prepare("INSERT INTO restaurantBookings(userID, restaurantID, numGuests, phoneNum, bookingDate, bookingTime, `status`, amount, msg) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $_SESSION['userID'], $_SESSION['restaurantID'], $_SESSION['numGuests'], $_SESSION['phoneNum'], $bookD, $bookT, $bookingStatus, $amount, $_SESSION['msg']);
    $result = $stmt->execute();
    $stmt->close();

    $bookingID = $conn->insert_id;

    $relatedTo = 'restaurant';
    $stmt = $conn->prepare("INSERT INTO payments(bookingID, userID, relatedTo, relatedID, `status`, firstName, amount, txnID, txnHash, txnKey, productInfo, email) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    echo $conn->error;
    $stmt->bind_param("ssssssssssss", $bookingID, $_SESSION['userID'], $relatedTo, $_SESSION['restaurantID'], $status, $firstname, $amount, $txnid, $hash, $key, $productinfo, $email);
    $result = $stmt->execute();
    $stmt->close();

    include 'includes/header.php';
    include 'includes/navbar.php';

    echo "<center style='color: #333;'>";
    echo "<h3>Your order status is ". $status .".</h3>";
    echo "<h4>Your transaction id for this transaction is ".$txnid.".</h4>";
    echo "</center>";

    include 'includes/footer.php';
  }
?>