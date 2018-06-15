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
    <div class="col-md-3"></div>
  </div>
</center>
<br/><br/>

<div class="row">
  <div class="container text-center">
    <div style="font-size: 15px;"> Recommended By Admins </div>
    <div style="font-size: 20px;"><b>Lorem Ipsum is the best language</b></div>
  </div>
</div>
<br/>

<div class="row">
  <div class="container">
    <?php
      $conn = new mysqli("localhost", "root", "", "codingCampus");
      $sql = "SELECT * FROM property";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("SELECT * FROM likes WHERE userID=? AND propertyID=?");
            $stmt->bind_param("ss", $_SESSION['userID'], $row['id']);
            $stmt->execute();
            $num = $stmt->get_result()->num_rows;
            $stmt->close();

                  echo('<div class="col-sm-12 col-md-3">
                  <div class="shop-card">
                  <div class="shop-image">
                  <a href="property.php?id=');
                  echo($row['id']);
                  echo('">
                    <img src="img/test1.png">
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
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-property-id="');
                  }
                  echo($row['id']);
                    echo('"></i>
                  </div>
                  <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
                    <a href="property.php?id=');
                      echo($row['id'].'" style="color: #333;">');
                    echo($row['name']);
                  echo('</b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($row['perHourRate']);
                  echo('</b> <span id="per">P/Hr</span></div>
                  <div id="perRate"><span id="per">Rs</span> <b>');
                  echo($row['perDayRate']);
                  echo('</b> <span id="per">P/Day</span></div>
                  <div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> Excellent
                  </div>
                </div>
              </div>
              </div>');
          }
      }
    ?>
    <!-- <div class="shop-card">
          <div class="shop-image">
            <img src="img/test1.png">
          </div>
          <div class="shop-content">
            <div id="name">
              <div style="width: 90%; float: left;"> 
                <b>COWORKING</b>
              </div>
              <i class="far fa-heart" style="font-size: 20px; float: left; color: #737373;"></i>
            </div>
            <div id="detail"><b>
              Lorem Ipsum is the most beautiful thing
            </b></div>
            <div id="perRate"><span id="per">Rs.</span> <b>100,00</b> <span id="per">P/Hr</span></div>
            <div id="perRate"><span id="per">Rs</span> <b>400,00</b> <span id="per">P/Day</span></div>
            <div class="starRate">
              <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> Excellent
            </div>
          </div>
        </div> -->
    <br>
  </div>
</div>

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