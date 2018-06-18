<?php
    session_start();
?>    
    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php include 'includes/header.php'; ?>

    <?php include 'includes/navbar.php';?>

        <div class="row">
            <div class="container">
            <h3 class="text-center">Your Bookings</h3><br/>

    <?php
        $i = 0;

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE userID=?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $result = $stmt->get_result();

        while($booking = $result->fetch_assoc()) {
            $i++;
            $stm = $conn->prepare("SELECT * FROM property WHERE id=?");
            $stm->bind_param("s", $booking['propertyID']);
            $stm->execute();
            $property = $stm->get_result()->fetch_assoc();
            $stm->close();

            $relatedTo = 'property';
            $st = $conn->prepare("SELECT * FROM `location` WHERE relatedTo=? AND relatedID=?");
            $st->bind_param("ss", $relatedTo, $booking['propertyID']);
            $st->execute();
            $location = $st->get_result()->fetch_assoc();
            $st->close();

            $relatedTo = 'property';
            $s = $conn->prepare("SELECT * FROM `images` WHERE relatedTo=? AND relatedID=?");
            $s->bind_param("ss", $relatedTo, $booking['propertyID']);
            $s->execute();
            $image = $s->get_result()->fetch_assoc();
            $s->close();
    ?>
            <div class="col-sm-12 col-md-6" style="padding-bottom: 30px;">
                <div style="padding-top: 15px; padding-bottom: 15px; overflow: hidden; border: 1px solid #bfbfbf; border-radius: 10px;">
                    <div class="col-sm-4 col-md-4">
                        <img src="<?php echo $image['location']; ?>" style="height: 150px !important; border-radius: 10px;">
                    </div>
                    <div class="col-sm-8 col-md-8"> 
                        <b style="color: #333; font-size: 18px;"><?php echo($property['name']); ?></b><br/>
                        <i><?php echo $location['city'].", ".$location['country']; ?></i><br/>
                        <b>CheckIn</b>: <u><?php echo date('d F Y', strtotime($booking['checkIn'])); ?></u><br/>
                        <b>CheckOut</b>: <u><?php echo date('d F Y', strtotime($booking['checkOut'])); ?></u><br/>
                        <b>Amount Paid</b>: <u><i>Rs. <?php echo $booking['amount']; ?></i></u><br/>
                    </div>
                </div>  
            </div>
    <?php
        }
        $stmt->close();
    ?>


    <?php
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM restaurantBookings WHERE userID=?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $result = $stmt->get_result();

        while($booking = $result->fetch_assoc()) {
            $i++;
            $stm = $conn->prepare("SELECT * FROM restaurants WHERE id=?");
            $stm->bind_param("s", $booking['restaurantID']);
            $stm->execute();
            $restaurant = $stm->get_result()->fetch_assoc();
            $stm->close();

            $relatedTo = 'restaurant';
            $st = $conn->prepare("SELECT * FROM `location` WHERE relatedTo=? AND relatedID=?");
            $st->bind_param("ss", $relatedTo, $booking['restaurantID']);
            $st->execute();
            $location = $st->get_result()->fetch_assoc();
            $st->close();

            $relatedTo = 'restaurant';
            $s = $conn->prepare("SELECT * FROM `images` WHERE relatedTo=? AND relatedID=?");
            $s->bind_param("ss", $relatedTo, $booking['restaurantID']);
            $s->execute();
            $image = $s->get_result()->fetch_assoc();
            $s->close();
    ?>
            <div class="col-sm-12 col-md-6" style="padding-bottom: 30px;">
                <div style="padding-top: 15px; padding-bottom: 15px; overflow: hidden; border: 1px solid #bfbfbf; border-radius: 10px;">
                    <div class="col-sm-4 col-md-4">
                        <img src="<?php echo $image['location']; ?>" style="height: 150px !important; border-radius: 10px;">
                    </div>
                    <div class="col-sm-8 col-md-8"> 
                        <b style="color: #333; font-size: 18px;"><?php echo($restaurant['name']); ?></b><br/>
                        <i><?php echo $location['city'].", ".$location['country']; ?></i><br/>
                        <b>Date</b>: <u><?php echo date('d F Y', strtotime($booking['bookingDate'])); ?></u><br/>
                        <b>Time</b>: <u><?php echo date('d F Y', strtotime($booking['bookingTime'])); ?></u><br/>
                        <b>Amount Paid</b>: <u><i>Rs. <?php echo $booking['amount']; ?></i></u><br/>
                    </div>
                </div>  
            </div>
    <?php
        }
        $stmt->close();

        if($i == 0) {
            echo('<h4 style="text-center;"><i> No bookings yet :(</i></h4>');
        }
    ?>
            </div>
        </div>  

<?php include 'includes/footer.php'; ?>