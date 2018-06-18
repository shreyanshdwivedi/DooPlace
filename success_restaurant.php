<?php
  ob_start();
  session_start();

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  //Load Composer's autoloader
  require dirname(__FILE__).'/vendor/autoload.php';

  $status      =$_POST["status"];
  $firstname   =$_POST["firstname"];
  $amount      =$_POST["amount"];
  $txnid       =$_POST["txnid"];
  $posted_hash =$_POST["hash"];
  $key         =$_POST["key"];
  $productinfo =$_POST["productinfo"];
  $email       =$_POST["email"];
  $salt        ="eCwWELxi";

  $restaurantID = $_SESSION['restaurantID'];
  $bookDate = $_SESSION['bookDate'];
  $bookTime = $_SESSION['bookTime'];
  $numGuests = $_SESSION['numGuests'];
  $price = $_SESSION['price'];

  If (isset($_POST["additionalCharges"])) {
    $additionalCharges =$_POST["additionalCharges"];
    $retHashSeq        = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
          
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
    $bookingStatus = 'success';
    $bookD = date('Y-m-d', strtotime($_SESSION['bookDate']));
    $bookT = date("H:i", strtotime($_SESSION['bookTime']));
    $conn = new mysqli("localhost", "root", "", "codingCampus");

    
    $stmt = $conn->prepare("INSERT INTO restaurantBookings(userID, restaurantID, numGuests, phoneNum, bookingDate, bookingTime, `status`, amount, msg) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $_SESSION['userID'], $_SESSION['restaurantID'], $_SESSION['numGuests'], $_SESSION['phoneNum'], $bookD, $bookT, $bookingStatus, $amount, $_SESSION['msg']);
    $result = $stmt->execute();
    $stmt->close();

    $bookingID = $conn->insert_id;

    // $query = "INSERT INTO restaurantBookings(userID, restaurantID, numGuests, phoneNum, bookingDate, bookingTime, `status`, amount, msg) values(".$_SESSION['userID'].", ".$_SESSION['restaurantID'].", ".$_SESSION['numGuests'].", ".$_SESSION['phoneNum'].", ".$bookD.", ".$bookT.", ".$bookingStatus.", ".$amount.", ".$_SESSION['msg'];

    // if($stm = $conn->prepare($query)){
    //     $stm->execute();
    //     $stm->close();

    //     $bookingID = $conn->insert_id;
    //     echo "Hi";
    // }
    // echo $conn->error;

    $relatedTo = 'restaurant';
    $stmt = $conn->prepare("INSERT INTO payments(bookingID, userID, relatedTo, relatedID, `status`, firstName, amount, txnID, txnHash, txnKey, productInfo, email) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    echo $conn->error;
    $stmt->bind_param("ssssssssssss", $bookingID, $_SESSION['userID'], $relatedTo, $_SESSION['restaurantID'], $status, $firstname, $amount, $txnid, $hash, $key, $productinfo, $email);
    $result = $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM restaurants WHERE id=?");
    $stmt->bind_param("s", $_SESSION['restaurantID']);
    $stmt->execute();
    $restaurant = $stmt->get_result()->fetch_assoc();
    $stmt->close(); 

    include 'includes/header.php';
    include 'includes/navbar.php';
                $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'test22091997@gmail.com';                 // SMTP username
                    $mail->Password = 'Wireless031';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                                    // TCP port to connect to

                    //Recipients
                    $mail->setFrom('test22091997@gmail.com', 'DooPlace');
                    $mail->addAddress($email, $firstname);     

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Booking Successful';
                    $mail->Body    = 'Congratulations!<br/> We are happy to inform you that your booking for <b>'.$restaurant['name'].'</b> is confirmed.<br/>
                                      Date - <b>'.$_SESSION['bookDate'].'</b><br/>
                                      Time - <b>'.$_SESSION['bookTime'].'</b><br/>
                                      Enjoy your visit!';

                    $mail->send();
                    $_SESSION['success'] = "Restaurant booking successful";
                    // header("Location: index.php");
                } catch (Exception $e) {
                  $_SESSION['error'] = "There was an error in booking!!";
                  header("Location: index.php");
                }

    echo "<center style='color: #333;'>";
    echo "<h3>Thank You. Your order status is ". $status .".</h3>";
    echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
    echo "<h4>We have received a payment of Rs. " . $amount . "</h4>";
    echo "</center>";

    include 'includes/footer.php';
    $conn->close();
  }         
?>	