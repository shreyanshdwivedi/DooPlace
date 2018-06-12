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

    <?php
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE userID=?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

        <div class="row">
            <div class="container">

    <?php
        while($booking = $result->fetch_assoc()) {
            $stm = $conn->prepare("SELECT * FROM property WHERE id=?");
            $stm->bind_param("s", $_SESSION['propertyID']);
            $stm->execute();
            $property = $stm->get_result()->fetch_assoc();
            $stm->close();

            $st = $conn->prepare("SELECT * FROM `location` WHERE propertyID=?");
            $st->bind_param("s", $_SESSION['propertyID']);
            $st->execute();
            $location = $st->get_result()->fetch_assoc();
            $st->close();
    ?>

            <div class="col-sm-12 col-md-6">
                <div style="padding-top: 15px; padding-bottom: 15px; overflow: hidden; border: 1px solid #bfbfbf;">
                    <div class="col-sm-4 col-md-4">
                        <img src="img/host_0.jpg" style="height: 100% !important;">
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
            </div>
        </div>  

<?php include 'includes/footer.php'; ?>