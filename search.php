<?php session_start(); ?>
<?php include 'includes/header.php'; ?>
<?php
   include 'includes/navbar.php'; 
   if(!empty($_POST)) {
     $street = $_POST['street'];
     $city = $_POST['city'];
     $state = $_POST['state'];
     $country = $_POST['country'];
     $zip = $_POST['zip'];
   } else if(!empty($_GET)){
     $city = $_GET['city'];
     $state = '';
     $country = '';
     $zip = '';
     $street = '';
   } else {
     $city = "London";
     $state = '';
     $country = '';
     $zip = '';
     $street = '';
   }

?>
    <br>
<br/>
<center>
  <div class="row">
    <div class="col-sm-12 col-md-2 text-center">
      Search Results<br/>
      <span style="font-size: 20px;">
        Lorem Ipsum
      </span>
    </div>
    <div class="col-sm-12 col-md-4">
      <div class="col-sm-9 col-md-10">
        <input type="text" name="search" placeholder="Search by Place.." id="searchBar_1" onFocus="geolocate()" style="padding: 8px 10px 6px 32px;">
      </div>

      <form action="" method="post">
            <input type="text" name="street" id="street_number" hidden>
            <input type="text" name="country" id="country" hidden>
            <input type="text" name="city" id="locality" hidden>
            <input type="text" name="state" id="administrative_area_level_1" hidden>  
            <input type="number" name="zip" id="postal_code" hidden>
            <div class="col-sm-3 col-md-2">
              <input type="submit" class="btn btn-default" value="Search">
            </div>
      </form>
    </div>
    <div class="col-sm-12 col-md-6">
      <!-- <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>       
      </div> -->
    </div>
  </div>
</center>
<br/><br/>

<div class="row">
  <div class="container text-center">
    <div style="font-size: 15px;">Search Results</div>
    <div style="font-size: 20px;"><b>Lorem Ipsum is the best language</b></div>
  </div>
</div>
<br/>

<div class="row">
  <div class="container">
    <div class="col-sm-12 col-md-6">

    <?php
        $relatedTo = 'property';
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM `location` WHERE (city=? OR `state`=? OR country=?) AND relatedTo=?");
        $stmt->bind_param("ssss", $city, $state, $country, $relatedTo);
        $stmt->execute();
        $result = $stmt->get_result();

    while($location = $result->fetch_assoc()) {
        $stm = $conn->prepare("SELECT * FROM property WHERE id=?");
        $stm->bind_param("s", $location['relatedID']);
        $stm->execute();
        $property = $stm->get_result()->fetch_assoc();
        $stm->close();

        $stmt = $conn->prepare("SELECT * FROM likes WHERE ((userID=? AND relatedTo=?) AND relatedID=?)");
        $stmt->bind_param("sss", $_SESSION['userID'], $relatedTo, $property['id']);
        $stmt->execute();
        $num = $stmt->get_result()->num_rows;
        $stmt->close();

        $relatedTo = 'property';
        $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
        $stmt->bind_param("ss", $relatedTo, $property['id']);
        $stmt->execute();
        $images = $stmt->get_result()->fetch_assoc();
        $stmt->close();

    echo('<div class="col-sm-12 col-md-6">
    <div class="shop-card">
    <div class="shop-image" style="border-radius: 5px;">
    <a href="property.php?id=');
    echo($property['id']);
    echo('">
      <img src="'.$images["location"].'" height="160px" width="100%">
    </a>
    </div>
    <div class="shop-content">
    <div id="name">
      <div style="width: 90%;');
    if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){ echo('float: left;');}
      echo('"><b>');
      echo(strtoupper($property['propertyType']));
    echo('</b>
      </div>');

    if($num>0) {
        echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="property" data-property-id="');
    } else {
        echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="property" data-property-id="');
    }
    echo($restaurant['id']);
      echo('"></i>
    </div>
    <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
      <a href="property.php?id=');
        echo($property['id'].'" style="color: #333;">');
      echo($property['name']);
    echo('</a></b></div>
    <div id="perRate"><span id="per">Rs</span> <b>');
    echo($property['perDayRate']);
    echo('</b> <span id="per">per night</span></div>
    <div class="starRate">
      <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
    </div>
  </div>
</div>
</div>');

    }

        $relatedTo = 'restaurant';
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM `location` WHERE (city=? OR `state`=? OR country=?) AND relatedTo=?");
        $stmt->bind_param("ssss", $city, $state, $country, $relatedTo);
        $stmt->execute();
        $result = $stmt->get_result();

    while($location = $result->fetch_assoc()) {
        $stm = $conn->prepare("SELECT * FROM restaurants WHERE id=?");
        $stm->bind_param("s", $location['relatedID']);
        $stm->execute();
        $restaurant = $stm->get_result()->fetch_assoc();
        $stm->close();

        $stmt = $conn->prepare("SELECT * FROM likes WHERE ((userID=? AND relatedTo=?) AND relatedID=?)");
        $stmt->bind_param("sss", $_SESSION['userID'], $relatedTo, $restaurant['id']);
        $stmt->execute();
        $num = $stmt->get_result()->num_rows;
        $stmt->close();

        $relatedTo = 'restaurant';
        $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
        $stmt->bind_param("ss", $relatedTo, $restaurant['id']);
        $stmt->execute();
        $images = $stmt->get_result()->fetch_assoc();
        $stmt->close();

    echo('<div class="col-sm-12 col-md-6">
    <div class="shop-card">
    <div class="shop-image" style="border-radius: 5px;">
    <a href="property.php?id=');
    echo($restaurant['id']);
    echo('">
      <img src="'.$images["location"].'" height="160px" width="100%">
    </a>
    </div>
    <div class="shop-content">
    <div id="name">
      <div style="width: 90%;');
    if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){ echo('float: left;');}
      echo('"><b>');
      echo(strtoupper($restaurant['cuisine']));
    echo('</b>
      </div>');

    if($num>0) {
        echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="property" data-property-id="');
    } else {
        echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="property" data-property-id="');
    }
    echo($restaurant['id']);
      echo('"></i>
    </div>
    <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
      <a href="property.php?id=');
        echo($restaurant['id'].'" style="color: #333;">');
      echo($restaurant['name']);
    echo('</a></b></div>
    <div id="perRate"><span id="per">Rs</span> <b>');
    echo($restaurant['price']);
    echo('</b> <span id="per">per person</span></div>
    <div class="starRate">
      <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
    </div>
  </div>
</div>
</div>');

    }
?>
</div>
    <div class="col-sm-12 col-md-6">
      <div style="width: 100%">
        <iframe width="100%" height="600" src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo($city); ?>%20<?php echo($state); ?>%20<?php echo($country); ?>+(DooPlace)&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
          <a href="https://www.mapsdirections.info/en/custom-google-maps/">www.mapsdirections.info</a> by <a href="https://www.mapsdirections.info/en/">Measure area on map</a>
        </iframe>
      </div><br />
    </div> 
  </div>
</div>
<br/><br/>
<div class="row">
  <div class="container text-center">
      <h3>Featured Cities</h3>
      <br/>
      <a href="search.php?city=Sydney" style="color:#333;">
        <div class="col-sm-12 col-md-3">
            <img src="img/sydney.jpg" height="250px">
            <b><i>Sydney</b></i>
        </div>
      </a>
      <a href="search.php?city=London" style="color:#333;">
        <div class="col-sm-12 col-md-3">
            <img src="img/london.jpg" height="250px">
            <b><i>London</b></i>
        </div>
      </a>
      <a href="search.php?city=Malaysia" style="color:#333;">
        <div class="col-sm-12 col-md-3">
            <img src="img/malaysia.jpg" height="250px">
            <b><i>Malaysia</b></i>
        </div>
      </a>
      <a href="search.php?city='New York'" style="color:#333;">
        <div class="col-sm-12 col-md-3">
            <img src="img/newyork.jpg" height="250px">
            <b><i>New York</b></i>
        </div>
      </a>
  </div>
</div>
<br/><br/>

<script>
        var placeSearch, autocomplete;
        var componentForm = {
            street_number: 'short_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('searchBar_1')),
                {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            console.log(place);

            for (var component in componentForm) {
              document.getElementById(component).value = '';
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
              var addressType = place.address_components[i].types[0];
              if (componentForm[addressType]) {
                  var val = place.address_components[i][componentForm[addressType]];
                  document.getElementById(addressType).value = val;
                  console.log(addressType+" "+val);
              }
            }
        }

        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.
        function geolocate() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                  var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                  };
                  var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                  });
                  autocomplete.setBounds(circle.getBounds());
              });
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8oc4HbLoQUnqWaZr7SFnpvcLBSG7iEio&libraries=places&callback=initAutocomplete"
            async defer></script>

<?php include 'includes/footer.php'; ?>