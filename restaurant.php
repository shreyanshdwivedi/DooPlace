<?php
    session_start();
    include 'includes/header.php';
    include 'includes/navbar.php';

    if(isset($_GET['id'])) {
        $_SESSION['restaurantID'] = $_GET['id'];
    }
    $restaurantID = $_SESSION['restaurantID'];
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
    
    $relatedTo = 'restaurant';
    $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
    $stmt->bind_param("ss", $relatedTo, $restaurant['id']);
    $stmt->execute();
    $images = $stmt->get_result();
    $stmt->close(); 


    $dd = explode(', ', $restaurant['closedDays']);
    $individual_days = array();
    foreach($dd as $d){
        $individual_days[] = $d;
    }
    $json_array_days = json_encode($individual_days);
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
                        <b><?php echo(strtoupper($restaurant['cuisine'])); ?></b>
                    </div>
                    <div class="property-title" style="font-size: 25px;">
                        <b><?php echo($restaurant['name']); ?></b>
                    </div>
                    <div class="property-location" style="font-size: 18px;">
                        <?php echo($location['city'].", ".$location['country']); ?>
                    </div>
                </diV>
            </div>    
            <br/><hr>
            <div class="row" style="font-size: 18px;">
                <?php $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'); ?>
                    <div class="col-sm-4 col-md-4">
                        <ul style="list-style: none;">
                            <li><b>Price Range</b></li>
                            <li><b>Working Hours</b></li>
                            <li><b>Days Closed</b></li>
                            <li><b>Capacity</b></li>
                        </ul>
                    </div>
                    <div class="col-sm-8 col-md-8">
                        <ul style="list-style: none;">
                            <li><i class="fas fa-rupee-sign"></i> <?php echo($restaurant['price']); ?> per person</li>
                            <li><?php echo date('h:i A', strtotime($restaurant['openingHr']))." - ".date('h:i A', strtotime($restaurant['closingHr']));?></li>
                            <li>
                                <?php 
                                    $i = 0;
                                    foreach($dd as $d) {
                                        if($i==0) {
                                            echo($dowMap[$d]);
                                        } else {
                                            echo(", ".$dowMap[$d]);
                                        }
                                        $i++;
                                    }
                                ?>
                            </li>
                            <li><?php echo $restaurant['capacity']; ?>
                        </ul>
                    </div>
            </div>
            <hr>
            <div class="property-details" style="font-size:18px; font-family: Roboto;">
                <?php 
                    if($restaurant['about'] != "") {
                        echo('<b>About</b><p>'.$restaurant['about'].'</p><br/>');
                    }
                ?>
            </div>
            <hr>
        </div>
        <div class="col-md-1"></div>
        <div class="col-sm-12 col-md-4" style="border: 1px solid #eee;">
            <div class="sell-card" style="padding-top: 20px;">
                <div class="rate" style="font-size: 20px;">
                    <b><i class="fas fa-rupee-sign"></i> <?php echo($restaurant['price']); ?></b> <span style="font-size: 15px;">per person</span><br/>
                    <div class="stars" style="font-size: 15px;">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>  
                    <hr>
                    <div>
                        <div class="alert alert-danger alert-dismissible" id="errorBookDiv" style="display:none; font-size: 12px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span id="errorBookForm"></span>
                        </div>
                        <form method="post" action="restaurantBooking.php">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" id="drb" name="bookDate" required>
                            </div>
                            <div class="form-group">
                                <label>Time</label>
                                <div class="input-group bootstrap-timepicker timepicker pull-right" style="width: 100%;">
                                    <input id="timepicker3" type="text" class="form-control input-small" name="bookTime">
                                </div>
                            </div><br/>
                            <div class="form-group">
                                <label>Guests</label>
                                <input type="number" class="form-control" name="numGuestsRB" id="numGuestsRB" min="1" max="10" required>
                            </div>
                            <input type="hidden" class="form-control" id="price" name="price" value="<?php echo($restaurant['price']); ?>"> 
                            <input type="hidden" class="form-control" name="restaurantID" value="<?php echo($restaurant['id']); ?>">
                            <hr>
                            <div class="form-group">
                                <input type="submit" class="btn btn-default btn-lg" name="bookRest" id="bookRest" value="Book Now">
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
    <div class="container">
        <iframe width="100%" height="400" src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo $address; ?>+(DooPlace)&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
          <a href="https://www.mapsdirections.info/en/custom-google-maps/">www.mapsdirections.info</a> by <a href="https://www.mapsdirections.info/en/">Measure area on map</a>
        </iframe>
     </div>
</div>

<!-- <div class="row" hidden>
    <input id="address" type="text" value="</?php echo $address; ?>">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
    $('#timepicker3').timepicker({
        minuteStep: 15,
        showInputs: false,
        disableFocus: true,
        defaultTime: '<?php echo date('h:i A', strtotime($restaurant['openingHr'])); ?>'
    }).on('changeTime.timepicker', function(e) {  
        console.log(e);  
        var h= e.time.hours;
        var m= e.time.minutes;
        var mer= e.time.meridian;
        //convert hours into minutes
        m+=h*60;
        //10:15 = 10h*60m + 15m = 615 min
        var o = '<?php echo date("h:i A", strtotime($restaurant["openingHr"])); ?>';
        var omer = '<?php echo date("A", strtotime($restaurant["openingHr"])); ?>';
        var om = <?php echo date("i", strtotime($restaurant["openingHr"])); ?>;
        var oh = <?php echo date("h", strtotime($restaurant["openingHr"])); ?>;
        om += oh*60;
        
        var c = '<?php echo date("h:i A", strtotime($restaurant["closingHr"])); ?>';
        var cmer = '<?php echo date("A", strtotime($restaurant["closingHr"]));?>';
        var cm = <?php echo date("i", strtotime($restaurant["closingHr"]));?>;
        var ch = <?php echo date("h", strtotime($restaurant["closingHr"]));?>;
        cm += ch*60;
        
        if(mer==omer && m<om) {
            $('#timepicker3').timepicker('setTime', o);
        } else if(mer==cmer && m>cm) {
            $('#timepicker3').timepicker('setTime', c);
        }
    });
</script>
