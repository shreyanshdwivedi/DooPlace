<center>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-sm-12 col-md-6">
    
      <div class="col-sm-9 col-md-10">
        <input type="text" name="search" placeholder="Search by Place.." id="searchBar" onFocus="geolocate()">
      </div>

      <form action="search.php" method="post">
            <input type="text" name="street" id="street_number" hidden>
            <input type="text" name="country" id="country" hidden>
            <input type="text" name="city" id="locality" hidden>
            <input type="text" name="state" id="administrative_area_level_1" hidden>  
            <input type="number" name="zip" id="postal_code" hidden>
            <div class="col-sm-3 col-md-2">
              <input type="submit" class="btn btn-default btn-lg" value="Search">
            </div>
      </form>
    </div>
    <div class="col-md-3"></div>
  </div>
  <br/>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-sm-12 col-md-6">
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;" id="dateFilter">Date</button>
        <input type="hidden" name="daterange" id="daterange"/>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" style="width: 100%;">Places</button>

        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select the place</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <a href="http://localhost/desktop/Website-CodingCampus/index.php?place=Restaurants">
                      <button type="button" class="btn btn-info" style="width: 100%;">Restaurants</button>
                    </a>
                  </div>
                  <div class="col-md-6">
                    <a href="http://localhost/desktop/Website-CodingCampus/index.php?place=Houses">
                      <button type="button" class="btn btn-info" style="width: 100%;">Houses</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Office Type</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;" disabled>Filter</button>       
      </div>
    </div>
    <div class="col-md-3"></div>
  </div>
</center>
<br/><br/>

<div class="row">
  <div class="container">
    <img src="https://a0.muscache.com/4ea/air/v2/pictures/5b64bcbf-d6b1-4cbf-b866-9b5f91027b35.jpg?t=c:w1131-h343,r:w1131-h343-sfit,e:fjpg-c75" width="100%" style="border-radius: 10px;">
  </div>
</div>
<br/>
<div class="row">
  <div class="container text-center">
    <h3>Recommended By Admins </h3>
  </div>
</div>

<?php
  $conn = new mysqli("localhost", "root", "", "codingCampus");
  if((isset($place) && $place=="Houses") || (!isset($place))) { 
?>
<div class="row">
  <div class="container">
    <h4 class="text-center"><b>Homes around world</b></h4>
    <br/><br/>
    <?php
      $sql = "SELECT * FROM property";
      $result = $conn->query($sql);
      $relatedTo = 'property';
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            if(isset($startDate) && isset($endDate)) {
              $stmt = $conn->prepare("SELECT * FROM bookings WHERE propertyID=? AND ((checkIn BETWEEN ? AND ?) OR (checkOut BETWEEN ? AND ?))");
              $stmt->bind_param("sssss", $row['id'], $startDate, $endDate, $startDate, $endDate);
              $stmt->execute();
              $num = $stmt->get_result()->num_rows;
              $stmt->close();
              if($num>0) {
                continue;
              }
            }
            $stmt = $conn->prepare("SELECT * FROM likes WHERE ((userID=? AND relatedTo=?) AND relatedID=?)");
            $stmt->bind_param("sss", $_SESSION['userID'], $relatedTo, $row['id']);
            $stmt->execute();
            $num = $stmt->get_result()->num_rows;
            $stmt->close();

            $relatedTo = 'property';
            $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
            $stmt->bind_param("ss", $relatedTo, $row['id']);
            $stmt->execute();
            $images = $stmt->get_result()->fetch_assoc();
            $stmt->close();

                  echo('<div class="col-sm-12 col-md-3">
                  <div class="shop-card">
                  <div class="shop-image" style="border-radius: 5px;">
                  <a href="property.php?id=');
                  echo($row['id']);
                  echo('">
                    <img src="'.$images["location"].'" height="160px" width="100%">
                  </a>
                  </div>
                  <div class="shop-content">
                  <div id="name">
                    <div style="width: 90%;');
                  if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){ echo('float: left;');}
                    echo('"><b>');
                    echo(strtoupper($row['propertyType']));
                  echo('</b>
                    </div>');

                  if($num>0) {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="property" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="property" data-property-id="');
                  }
                  echo($row['id']);
                    echo('"></i>
                  </div>
                  <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
                    <a href="property.php?id=');
                      echo($row['id'].'" style="color: #333;">');
                    echo($row['name']);
                  echo('</a></b></div>
                  <div id="perRate"><span id="per">Rs</span> <b>');
                  echo($row['perDayRate']);
                  echo('</b> <span id="per">per night</span></div>
                  <div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                  </div>
                </div>
              </div>
              </div>');
          }
      }
    ?>
    <br>
  </div>
</div>
<br/><br/>

<div class="row">
  <div class="container">
    <img src="https://getbento.imgix.net/accounts/f7efa3ac70fc351a1aa21cbd6bbe24c6/media/images/32760Bresca_Moss_Wall_2_Rey_Lopez.jpg?fit=max&w=1800&auto=format,compress" width="100%" style="border-radius: 10px;" height="400px">
  </div>
</div>
<br/>

<?php
  }
  if((isset($place) && $place=="Restaurants") || (!isset($place))){
?>
<div class="row">
  <div class="container">
    <h4 class="text-center"><b>Restaurants around world</b></h4>
    <br/><br/>
    <?php
      $sql = "SELECT * FROM restaurants";
      $result = $conn->query($sql);
      $relatedTo = 'restaurant';

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("SELECT * FROM likes WHERE ((userID=? AND relatedTo=?) AND relatedID=?)");
            $stmt->bind_param("sss", $_SESSION['userID'], $relatedTo, $row['id']);
            $stmt->execute();
            $num = $stmt->get_result()->num_rows;
            $stmt->close();

            $relatedTo = 'restaurant';
            $stmt = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
            $stmt->bind_param("ss", $relatedTo, $row['id']);
            $stmt->execute();
            $images = $stmt->get_result()->fetch_assoc();
            $stmt->close();

                  echo('<div class="col-sm-12 col-md-3">
                  <div class="shop-card">
                  <div class="shop-image">
                  <a href="restaurant.php?id=');
                  echo($row['id']);
                  echo('">
                    <img src="'.$images["location"].'" height="160px">
                  </a>
                  </div>
                  <div class="shop-content">
                  <div id="name">
                    <div style="width: 90%;');
                  if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){ echo('float: left;');}
                    echo('"><b>');
                    echo(strtoupper($row['cuisine']));
                  echo('</b>
                    </div>');

                  if($num>0) {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="restaurant" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="restaurant" data-property-id="');
                  }
                  echo($row['id']);
                    echo('"></i>
                  </div>
                  <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
                    <a href="restaurant.php?id=');
                      echo($row['id'].'" style="color: #333;">');
                    echo($row['name']);
                  echo('</a></b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($row['price']);
                  echo('</b> <span id="per">per person</span></div>');
                  echo('<div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                  </div>
                </div>
              </div>
              </div>');
          }
      }
    ?>
    <br>
  </div>
</div>

<?php
  }
?>
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
                /** @type {!HTMLInputElement} */(document.getElementById('searchBar')),
                {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();

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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8oc4HbLoQUnqWaZr7SFnpvcLBSG7iEio&libraries=places&callback=initAutocomplete&sensor=false"
            async defer></script>
            
    <div class="row">
        <div class="container">
            <div class="col-sm-12 col-md-6">
              <video width="100%" height="400" controls>
                  <source src="../b.mp4" type="video/mp4">
                  Sorry, your browser doesn't support the video element.
              </video>
                <!-- <iframe width="100%" height="400" src="https://www.youtube.com/embed/vD42pe4VVkA" frameborder="0" allowfullscreen></iframe> -->
            </div>
            <div class="col-md-6">
                <br/><br>
                <h3>This is the heading</h3><br/>
                <p style="font-size: 18px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
            </div>
        </div>
    </div>