<?php
    ob_start();
    session_start();

    include 'includes/fb-login.php';
    
    require_once('includes/gmail-setting.php');
    $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';

    include 'includes/header.php';
    include 'includes/navbar.php';

    $numGuests = "";
    $bookDate = "";
    $amount = "";
    $restaurantID = "";
    $bookTime = "";
    $price = "";

    if(isset($_POST['numGuestsRB']) && isset($_POST['bookDate']) && isset($_POST['restaurantID']) && isset($_POST['bookTime']) && isset($_POST['price'])){
        $numGuests = $_POST['numGuestsRB'];
        $bookDate = $_POST['bookDate'];
        $bookTime = $_POST['bookTime'];
        $price = $_POST['price'];
        $restaurantID = $_POST['restaurantID'];
        $amount = $numGuests*$price*1.18;

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM restaurants WHERE id=?");
        $stmt->bind_param("s", $restaurantID);
        $stmt->execute();
        $restaurant = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        $relatedTo = 'restaurant';
        $stmt = $conn->prepare("SELECT * FROM `location` WHERE relatedTo=? AND relatedID=?");
        $stmt->bind_param("ss", $relatedTo, $restaurantID);
        $stmt->execute();
        $location = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        header("Location: index.php");
    }
    $dateBook = date('d F Y', strtotime($bookDate));
?>
        <div class="row">
            <div class="container">
                <div class="col-md-1">
                </div>
                <div class="col-md-10 col-sm-12">
                    <div class="col-sm-7 col-md-7">
                        <form method="post" action="PayUMoney_restaurant.php">
                            <h3><b> Who's coming? </b></h3>
                            <div class="form-group">
                                <label> Guests</label>
                                <input type="number" class="form-control" value="<?php echo $numGuests; ?>" name="numGuests" readonly>
                            </div>
                            <div class="form-group">
                                <label>Say hello to your host</label>
                                <textarea class="form-control" placeholder="Let your host know a little about yourself" rows="4" name="msg"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Add your phone number</label>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <label style="font-size: 10px;">COUNTRY</label>
                                        <input type="text" name="countryCode" class="form-control">
                                    </div>
                                    <div class="col-sm-8 col-md-8">
                                        <label style="font-size: 10px;">PHONE NUMBER</label>
                                        <input type="text" name="phoneNumber" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="bookDate" value="<?php echo($bookDate); ?>">
                            <input type="hidden" class="form-control" name="bookTime" value="<?php echo($bookTime); ?>">
                            <input type="hidden" class="form-control" name="numGuests" value="<?php echo($numGuests); ?>">
                            <input type="hidden" class="form-control" name="amount" id="amount" value="<?php echo($amount); ?>">
                            <input type="hidden" class="form-control" name="price" id="price" value="<?php echo($price); ?>">
                            <input type="hidden" class="form-control" name="restaurantID" value="<?php echo($restaurantID); ?>">
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="Continue" name="bookRestaurant">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-5 col-md-5" style="border: 1px solid #eee;">
                        <div style="padding-top: 10px; height: 80px;">
                            <div class="col-sm-5 col-md-5"> 
                                <img src="img/host_0.jpg" max-width="100%" height="75px">
                            </div>
                            <div class="col-sm-7 col-md-7" style="float: right; font-size: 18px;">
                                <span><b><?php echo($restaurant['name']); ?></b></span>
                            </div>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <p> <?php echo($numGuests); ?> Guests </p>
                            <p> <?php echo($dateBook); ?> - <?php echo($bookTime); ?> </p>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <div> 
                                <p style="float: right;">Base Price <p>
                                <p>Rs. <?php echo($restaurant['price']*$numGuests); ?></p>
                            </div>
                            <div> 
                                <p style="float: right;">Service Fee <p>
                                <p>Rs. <?php echo(0.18*$restaurant['price']*$numGuests); ?></p>
                            </div>
                            <hr>
                            <div> 
                                <p style="float: right;">Total <p>
                                <p>Rs. <?php echo($amount); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>