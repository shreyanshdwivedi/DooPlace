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

    $relatedTo = 'property';
    $stmt = $conn->prepare("SELECT * FROM `location` WHERE relatedTo=? AND relatedID=?");
    $stmt->bind_param("ss", $relatedTo, $propertyID);
    $stmt->execute();
    $location = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $address = "";
    if($location['suite']!=""){
        $address.=$location['suite']."%20";
    }
    if($location['street']!=""){
        $address.=$location['street']."%20";
    }
    if($location['city']!=""){
        $address.=$location['city']."%20";
    }
    if($location['state']!=""){
        $address.=$location['state']."%20";
    }
    if($location['zip']!=""){
        $address.=$location['zip']."%20";
    }
    
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
    
    $relatedTo = 'property';
    $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
    $stmt->bind_param("ss", $relatedTo, $property['id']);
    $stmt->execute();
    $images = $stmt->get_result();
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
<?php
    $i = 0;
    foreach($images as $img) {
        if($i<=1) {
?>
    <div class="col-sm-12 col-md-6">
        <a href="<?php echo $img["location"]; ?>" data-lightbox="roadtrip">
            <img src="<?php echo $img["location"]; ?>" style="height:400px; width: 100%;">
        </a>
    </div>

<?php
    
        $i++;
        }
    }
    echo('</div>');
    
    foreach($images as $img) {
        echo('<a href="'.$img['location'].'" data-lightbox="roadtrip"></a>');
    }
?>
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
                        <?php
                            if($owner['image'] == "") {
                                echo('<img src="img/user.jpg" style="height: 100px; width: 100px; border-radius: 50%;">');
                            } else {
                                echo('<img src="'.$owner['image'].'" style="height: 100px; width: 100px; border-radius: 50%;">');
                            }
                        ?>
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
                <?php if($property['amenities'] != ""){ ?>
                <b>Amenities</b><br/><br/>
                <div class="row">
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
                    }
                    ?>
                
                <br/>
                <?php if($property['safetyAmenities'] != ""){ ?>
                <b>Safety Amenities</b><br/><br/>
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
                    }
                    ?>
                </div>
            </div>
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
<br/>
<div class="row">
    <div style="width: 100%">
        <iframe width="100%" height="600" src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo $address; ?>+(DooPlace)&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
          <a href="https://www.mapsdirections.info/en/custom-google-maps/">www.mapsdirections.info</a> by <a href="https://www.mapsdirections.info/en/">Measure area on map</a>
        </iframe>
     </div><br />
</div>

<!-- <div class="row" hidden>
    <input id="address" type="text" value="<?php echo $address; ?>">
</div>
<div id="map"></div> -->

<?php include 'includes/footer.php'; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8oc4HbLoQUnqWaZr7SFnpvcLBSG7iEio&callback=initMap"
            async defer></script>
<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
           zoom: 8,
           center: {lat: -34.397, lng: 150.644}
        });
        var geocoder = new google.maps.Geocoder();
        console.log(map);
        console.log(geocoder);
        // geocodeAddress(geocoder, map);
    }

    function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        console.log(address);
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
    }
</script>