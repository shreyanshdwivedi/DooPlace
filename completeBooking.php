<?php
    ob_start();
    session_start();
    include 'includes/header.php';
    include 'includes/navbar.php';

    $numGuests = "";
    $checkIn = "";
    $checkOut = "";
    $amount = "";
    $propertyID = "";
    $numDays = "";

    if(isset($_POST['numGuests']) && isset($_POST['checkIn']) && isset($_POST['checkOut']) && isset($_POST['amount']) && isset($_POST['propertyID']) && isset($_POST['numDays'])){
        $numGuests = $_POST['numGuests'];
        $checkIn = $_POST['checkIn'];
        $checkOut = $_POST['checkOut'];
        $amount = $_POST['amount'];
        $propertyID = $_POST['propertyID'];
        $numDays = $_POST['numDays'];

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM property WHERE id=?");
        $stmt->bind_param("s", $propertyID);
        $stmt->execute();
        $property = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        $stmt = $conn->prepare("SELECT * FROM `location` WHERE propertyID=?");
        $stmt->bind_param("s", $propertyID);
        $stmt->execute();
        $location = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        $stmt = $conn->prepare("SELECT * FROM `description` WHERE propertyID=?");
        $stmt->bind_param("s", $propertyID);
        $stmt->execute();
        $details = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        header("Location: index.php");
    }
    $checkInDate = date('d F Y', strtotime($checkIn));
    $checkOutDate = date('d F Y', strtotime($checkOut));
    $step = $_GET['step'];
    if($step == 0) {
?>

<div class="row">
    <div class="container">
        <div class="col-md-1">

        </div>
        <div class="col-md-10 col-sm-12">
            <div class="col-sm-7 col-md-7">
                <ul style="font-size: 18px; line-height: 2;">
                <h3><b>Space Rules</b></h3>
                    <li>Shoe-less in apartment</li>
                    <li>No Drugs</li>
                    <li>No "Entertaining Guest"</li>
                    <li>Smoking outside of private balcony</li>
                    <li>Full use of kitchen, clean after use and put grocery in dishwasher</li>
                    <li>Please turn off AC when you leave apartment</li>
                    <li>$30.00 for loss of keys</li>
                    <li>MUST ENJOY YOUR VISIT</li><br/>
                    <form action="completeBooking.php?step=1" method="post"> 
                        <input type="hidden" class="form-control" name="checkIn" value="<?php echo($checkIn); ?>">
                        <input type="hidden" class="form-control" name="checkOut" value="<?php echo($checkOut); ?>">
                        <input type="hidden" class="form-control" name="numGuests" value="<?php echo($numGuests); ?>">
                        <input type="hidden" class="form-control" name="amount" id="amount" value="<?php echo($amount); ?>">
                        <input type="hidden" class="form-control" name="numDays" id="numDays" value="<?php echo($numDays); ?>">
                        <input type="hidden" class="form-control" name="propertyID" value="<?php echo($propertyID); ?>">
                        <div class="form-group">
                            <input type="submit" class="btn btn-success btn-lg" value="Agree & Continue" style="width: 100%;" name="agree">
                        </div>
                    </form>
                </ul>
            </div>
            <div class="col-sm-5 col-md-5" style="border: 1px solid #eee;">
                    <div style="padding-top: 10px; height: 80px;">
                        <div class="col-sm-5 col-md-5"> 
                            <img src="img/host_0.jpg" max-width="100%" height="75px">
                        </div>
                        <div class="col-sm-7 col-md-7" style="float: right; font-size: 18px;">
                            <span><b><?php echo($property['name']); ?></b></span>
                        </div>
                    </div>
                    <hr>
                    <div style="line-height: 2;">
                        <p> <?php echo($numGuests); ?> Guests </p>
                        <p> <?php echo($checkInDate); ?> - <?php echo($checkOutDate); ?> </p>
                    </div>
                    <hr>
                    <div style="line-height: 2;">
                        <div> 
                            <p style="float: right;"><?php echo($property['perDayRate']); ?> * <?php echo($numDays); ?> Night <p>
                            <p>Rs. <?php echo($amount); ?></p>
                        </div>
                        <div> 
                            <p style="float: right;">Service Fee <p>
                            <p>Rs. <?php echo(0.18*$property['perDayRate']); ?></p>
                        </div>
                        <hr>
                        <div> 
                            <p style="float: right;">Total <p>
                            <p>Rs. <?php echo($amount + (0.18*$property['perDayRate'])); ?></p>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-md-1">

        </div>
    </div>
</div>

    <?php }
        if($step == 1) {
            if(!isset($_POST['agree'])) {
                header('Location: index.php');
            }
    ?>

        <div class="row">
            <div class="container">
                <div class="col-md-1">
                </div>
                <div class="col-md-10 col-sm-12">
                    <div class="col-sm-7 col-md-7">
                        <form method="post" action="completeBooking.php?step=2">
                            <h3><b> Who's coming? </b></h3>
                            <div class="form-group">
                                <label> Adults</label>
                                <select class="form-control" name="adultGuests">
                                    <option value="1">1 Adult</option>
                                    <option value="2">2 Adults</option>
                                    <option value="3">3 Adults</option>
                                    <option value="4">4 Adults</option>
                                    <option value="5">5 Adults</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Children</label>
                                <select class="form-control" name="childGuests">
                                    <option value="1">1 Child</option>
                                    <option value="2">2 Children</option>
                                    <option value="3">3 Children</option>
                                    <option value="4">4 Children</option>
                                    <option value="5">5 Children</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Say hello to your host</label>
                                <textarea class="form-control" placeholder="Let your host know a little about yourself" rows="4"></textarea>
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
                            <input type="hidden" class="form-control" name="checkIn" value="<?php echo($checkIn); ?>">
                            <input type="hidden" class="form-control" name="checkOut" value="<?php echo($checkOut); ?>">
                            <input type="hidden" class="form-control" name="numGuests" value="<?php echo($numGuests); ?>">
                            <input type="hidden" class="form-control" name="amount" id="amount" value="<?php echo($amount); ?>">
                            <input type="hidden" class="form-control" name="numDays" id="numDays" value="<?php echo($numDays); ?>">
                            <input type="hidden" class="form-control" name="propertyID" value="<?php echo($propertyID); ?>">
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="Continue" name="bookOne">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-5 col-md-5" style="border: 1px solid #eee;">
                        <div style="padding-top: 10px; height: 80px;">
                            <div class="col-sm-5 col-md-5"> 
                                <img src="img/host_0.jpg" max-width="100%" height="75px">
                            </div>
                            <div class="col-sm-7 col-md-7" style="float: right; font-size: 18px;">
                                <span><b><?php echo($property['name']); ?></b></span>
                            </div>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <p> <?php echo($numGuests); ?> Guests </p>
                            <p> <?php echo($checkInDate); ?> - <?php echo($checkOutDate); ?> </p>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <div> 
                                <p style="float: right;"><?php echo($property['perDayRate']); ?> * <?php echo($numDays); ?> Night <p>
                                <p>Rs. <?php echo($amount); ?></p>
                            </div>
                            <div> 
                                <p style="float: right;">Service Fee <p>
                                <p>Rs. <?php echo(0.18*$property['perDayRate']); ?></p>
                            </div>
                            <hr>
                            <div> 
                                <p style="float: right;">Total <p>
                                <p>Rs. <?php echo($amount + (0.18*$property['perDayRate'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>

    <?php } ?>

    <?php
        if($step == 2) {
            if(isset($_POST['bookOne'])) {
                $adults = $_POST['adultGuests'];
                $children = $_POST['childGuests'];
                $countryCode = $_POST['countryCode'];
                $phoneNum = $_POST['phoneNumber'];
            }
    ?>

        <div class="row">
            <div class="container">
                <div class="col-md-1">
                </div>
                <div class="col-md-10 col-sm-12">
                    <div class="col-sm-7 col-md-7">
                            <?php include 'payumoney/PayUMoney_form.php'; ?> 
                    </div>
                    <div class="col-sm-5 col-md-5" style="border: 1px solid #eee;">
                        <div style="padding-top: 10px; height: 80px;">
                            <div class="col-sm-5 col-md-5"> 
                                <img src="img/host_0.jpg" max-width="100%" height="75px">
                            </div>
                            <div class="col-sm-7 col-md-7" style="float: right; font-size: 18px;">
                                <span><b><?php echo($property['name']); ?></b></span>
                            </div>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <p> <?php echo($numGuests); ?> Guests </p>
                            <p> <?php echo($checkInDate); ?> - <?php echo($checkOutDate); ?> </p>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <div> 
                                <p style="float: right;"><?php echo($property['perDayRate']); ?> * <?php echo($numDays); ?> Night <p>
                                <p>Rs. <?php echo($amount); ?></p>
                            </div>
                            <div> 
                                <p style="float: right;">Service Fee <p>
                                <p>Rs. <?php echo(0.18*$property['perDayRate']); ?></p>
                            </div>
                            <hr>
                            <div> 
                                <p style="float: right;">Total <p>
                                <p>Rs. <?php echo($amount + (0.18*$property['perDayRate'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>

    <?php } ?>