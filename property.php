<?php
    session_start();
    include 'includes/header.php';
    include 'includes/navbar.php';

    if(isset($_GET['id'])) {
        $propertyID = $_GET['id'];
        $_SESSION['propertyID'] = $propertyID;
    }
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

    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("s", $property['userID']);
    $stmt->execute();
    $owner = $stmt->get_result()->fetch_assoc();
    $stmt->close(); 

    //below function returns all the dates within a given range
    function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {//if it was simple y example y-m-d then it will show 17 instead of 2017

       $dates = array();
       $current = strtotime($first);
       $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }//end of while loop

            return $dates;//returns a array
    }//end of function
    //select all the  date ranges that exist in the database 
    $sql = "SELECT checkIn, checkOut FROM bookings WHERE propertyID=".$_SESSION['propertyID'];
    $result = $conn->query($sql);
    
    $i = 0;

    while ($row=mysqli_fetch_assoc($result)){
        //output all the dates within the ranges in the database
        $range[$i] = date_range($row['checkIn'], $row['checkOut']);//creates a associative array with numerical index values
        $i++;     
    }

    $individual_dates = array();

    if($i>0){
        foreach($range as $ranges){
            foreach ($ranges as $many_ranges){
                $individual_dates[] = $many_ranges;
            }
        }
    }
    $json_array = json_encode($individual_dates);
?>

<div class="row">
    <a href="img/cover.jpeg" data-lightbox="roadtrip">
        <img src="img/cover.jpg" style="height:400px; width: 100%;">
    </a>
</div>

<a href="img/host_1.jpg" data-lightbox="roadtrip"></a>
<a href="img/host_2.jpg" data-lightbox="roadtrip"></a>
<a href="img/host_3.jpg" data-lightbox="roadtrip"></a>
<br>
<br>
<div class="row">
    <div class="container">
        <div class="col-sm-12 col-md-7">
            <div class="row">
                <div class="col-sm-9 col-md-9">
                    <div class="property-type" style="font-size: 12px;">
                        <b><?php echo(strtoupper($property['propertyType'])); ?></b>
                    </div>
                    <div class="property-title" style="font-size: 25px;">
                        <b><?php echo($property['name']); ?></b>
                    </div>
                    <div class="property-location" style="font-size: 18px;">
                        <?php echo($location['city'].", ".$location['country']); ?>
                    </div>
                </diV>
                <div class="col-sm-3 col-md-3 text-center">
                    <a href="viewProfile.php?userID=<?php echo $owner['id']; ?>" style="color: #333;">
                        <img src="img/user.jpg" style="height: 100px; width: 100px; border-radius: 50%;">
                        <b><?php echo $owner['first_name']; ?></b>
                    </a>
                </div>
            </div>    
            <br/>
            <div class="property-num">
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-users"></i> <?php echo($property['numGuests']); ?> Guests
                </div>
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-door-open"></i> <?php echo($property['numBedrooms']); ?> Bedrooms
                </div>
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-bed"></i> <?php echo($property['numBeds']); ?> Beds
                </div>
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-bath"></i> <?php echo($property['numBathrooms']); ?> Bathrooms
                </div>
            </div>
            <br/><br/><br/>
            <hr>
            <div class="property-details" style="font-size:18px; font-family: Roboto;">
                <?php 
                    if($details['summary'] != "") {
                        echo('<b>Summary</b><p>'.$details['summary'].'</p><br/>');
                    }
                    if($details['aboutPlace'] != "") {
                        echo('<b>The Space</b><p>'.$details['summary'].'</p><br/>');
                    }
                    if($details['access'] != "") {
                        echo('<b>Guest Access</b><p>'.$details['summary'].'</p><br/>');
                    }
                ?>
            </div>
            <hr>
            <div class="property-amenities" style="font-size:18px; font-family: Roboto;">
                <b>Amenities</b><br/><br/>
                <div>
                    <?php
                        $amenity = explode(',', $property['amenities']);
                        $i = 0;
                        foreach($amenity as $a) {
                            $i++;
                            if(!($i == sizeof($amenity))) {
                                echo('
                                <div class="col-sm-6 col-md-6">
                                    <i class="fas fa-angle-double-right"></i> '.ucfirst($a).' <br/><br/>
                                </div>');
                            }
                        }
                    ?>
                </div><br/><br/>
                <b>Safety Amenities</b><br/><br/>
                <div>
                    <?php
                        $amenity = explode(',', $property['safetyAmenities']);
                        $i = 0;
                        foreach($amenity as $a) {
                            $i++;
                            if(!($i == sizeof($amenity))) {
                                echo('
                                <div class="col-sm-6 col-md-6">
                                    <i class="fas fa-angle-double-right"></i> '.ucfirst($a).' <br/><br/>
                                </div>');
                            }
                        }
                    ?>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-md-1"></div>
        <div class="col-sm-12 col-md-4" style="border: 1px solid #eee;">
            <div class="sell-card" style="padding-top: 20px;">
                <div class="rate" style="font-size: 20px;">
                    <b>Rs. <?php echo($property['perDayRate']); ?></b> <span style="font-size: 15px;">per night</span><br/>
                    <div class="stars" style="font-size: 15px;">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>  
                    <hr>
                    <div>
                        <div class="alert alert-danger alert-dismissible" id="errorBookDiv" style="display:none; font-size: 12px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span id="errorBookForm"></span>
                        </div>
                        <form method="post" action="completeBooking.php?step=0">
                            <div class="form-group">
                                <label>Check In</label>
                                <input type="text" class="form-control" id="dt1" name="checkIn">
                            </div>
                            <div class="form-group">
                                <label>Check Out</label>
                                <input type="text" class="form-control" id="dt2" name="checkOut">
                            </div>
                            <div class="form-group">
                                <label>Guests</label>
                                <input type="number" class="form-control" name="numGuests" id="numGuests" min="1" max="10">
                            </div>
                            <input type="hidden" class="form-control" id="perDayRate" name="perDayRate" value="<?php echo($property['perDayRate']); ?>"> 
                            <input type="hidden" class="form-control" name="amount" id="amount">
                            <input type="hidden" class="form-control" name="numDays" id="numDays">
                            <input type="hidden" class="form-control" name="propertyID" value="<?php echo($property['id']); ?>">
                            <div id="bookingSummary" style="display: none; font-size: 15px;">
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="submit" class="btn btn-default btn-lg" name="book" id="book" value="Book Now" disabled>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>