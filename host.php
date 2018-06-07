<?php 
        session_start(); 
        if(!($_SESSION['isLoggedIn'] == true)) {
            header('Location: index.html');
        }    
    ?>   

    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php 
        include 'includes/header.php'; 
        include 'includes/navbar.php';
        $step = $_GET['step'];
        $conn = new mysqli("localhost", "root", "", "codingCampus");

        $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=? AND paramValue=?");
        $stmt->bind_param("ss", $_SESSION['loginType'], $_SESSION['paramValue']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 
    ?>

    <?php if($step == 0) { ?>

        <div class="row">
            <div class="container">
                <div class="col-sm-12 col-md-6">
                    <div>
                        <h3><b>Hi there, <?php echo $user['first_name']; ?> </b></h3><br/>
                        <p style="font-size: 20px;">We’re excited to learn about the experience you’d like to host on DooPlace.</p>
                        <p style="font-size: 20px;">In just a few minutes, you’ll start to create your experience page, then you’ll submit it to be reviewed by DooPlace.</p>
                        <a href="host.php?step=1">
                            <button class="btn btn-default btn-lg">Next</button>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <img src="img/host_0.jpg">
                </div>
            </div>
        </div>

    <?php } ?>

    <?php if($step == 1) { ?>
        
        <div class="row">
            <div class="container">
                <div class="col-sm-12 col-md-6">
                    <div>
                        <h3><b>What kind of place are you listing?</b></h3><br/>
                        <form method="post" action="host.php?step=2">
                            <label>First, let’s narrow things down</label>
                            <select id="property-type-group" name="property-type-group" class="form-control" required>
                                <option value="0">Select one</option>
                                <option value="apartments" >Apartments</option>
                                <option value="houses">Houses</option>
                                <option value="secondary_units">Secondary units</option>
                                <option value="unique_homes">Unique homes</option>
                                <option value="bnb">Bed and breakfasts</option>
                                <option value="boutique_hotels_and_more">Boutique hotels and more</option>
                            </select>
                            <br/><br/>
                            <label>Now choose a property type</label>
                            <select id="property-type-category" name="property-type-category" class="form-control" required disabled="disabled">
                                <option value="0">Select property type</option>
                                <option value="house">House</option>
                                <option value="bungalow">Bungalow</option>
                                <option value="cabin">Cabin</option>
                                <option value="casa_particular">Casa particular (Cuba)</option>
                                <option value="chalet">Chalet</option>
                                <option value="cottage">Cottage</option>
                                <option value="cycladic_house">Cycladic house (Greece)</option>
                                <option value="dammuso">Dammuso (Italy)</option>
                                <option value="dome_house">Dome house</option>
                                <option value="earthhouse">Earth house</option>
                                <option value="farm_stay">Farm stay</option>
                                <option value="houseboat">Houseboat</option>
                                <option value="hut">Hut</option>
                                <option value="lighthouse">Lighthouse</option>
                                <option value="pension">Pension (South Korea)</option>
                                <option value="shepherds_hut">Shepherd's hut (U.K., France)</option>
                                <option value="tiny_house">Tiny house</option>
                                <option value="townhouse">Townhouse</option>
                                <option value="trullo">Trullo (Italy)</option>
                                <option value="villa">Villa</option>
                            </select>
                            <br/><br/>
                            <label>What will guests have?</label>
                            <select id="room-type" name="room-type" class="form-control" disabled="disabled">
                                <option value="0">Select Room Type</option>
                                <option value="entire_home">Entire place</option>
                                <option value="private_room">Private room</option>
                                <option value="shared_room">Shared room</option>
                            </select>
                            <br/>
                            <a href="host.php?step=0">
                                <button class="btn btn-default btn-lg">Back</button>
                            </a>
                            <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="property" id="property">
                        </form>
                        <script>
                            document.getElementById('property').disabled = true;
                            document.getElementById("property-type-group").onchange=function() {
                                var group = document.getElementById('property-type-group').value;
                                if(group == "0") {
                                    document.getElementById('property-type-category').setAttribute('disabled', 'disabled');
                                    document.getElementById('room-type').setAttribute('disabled', 'disabled');
                                } else {
                                    document.getElementById('property-type-category').removeAttribute('disabled');
                                }
                            }
                            document.getElementById("property-type-category").onchange=function() {
                                var category = document.getElementById('property-type-category').value;
                                if(category == "0") {
                                    document.getElementById('room-type').setAttribute('disabled', 'disabled');
                                } else {
                                    document.getElementById('room-type').removeAttribute('disabled');
                                }
                            }
                            document.getElementById("room-type").onchange=function() {
                                var room = document.getElementById('room-type').value;
                                if(room == "0") {
                                    document.getElementById('property').disabled = true;
                                } else {
                                    document.getElementById('property').disabled = false;
                                }
                            }
                            console.log($group + " " + $category + " " + $type);
                        </script>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <img src="img/host_1.jpg">
                </div>
            </div>
        </div>

    <?php } ?>

    <?php
        if($step == 2) { 
            if(isset($_POST['property'])) {
                $group = $_POST['property-type-group'];
                $category = $_POST['property-type-category'];
                $type = $_POST['room-type'];
            }
    ?>

        <div class="row">
            <div class="container">
                <div class="col-sm-12 col-md-6">
                <form method="post" action="host.php?step=3">
                    <h3><b>How many guests can your place accommodate?</b></h3>
                    <label>Number of guests</label>
                    <i class="far fa-minus-square" style="font-size: 25px; margin-right: 20px; margin-left: 20px;"></i>
                    <input type="number" style="font-size: 20px; width: 60px;" id="numGuests" value="1" name="numGuests" disabled>
                    <i class="far fa-plus-square" style="font-size: 25px; margin-left: 20px;"></i>
                    <br/><br/><br/>
                    <label>How many bedrooms can guests use?</label>
                    <select id="bedroom-select" name="bedrooms" class="form-control">
                        <option value="0">Studio</option><option value="1">1 bedroom</option><option value="2">2 bedrooms</option><option value="3">3 bedrooms</option><option value="4">4 bedrooms</option><option value="5">5 bedrooms</option><option value="6">6 bedrooms</option><option value="7">7 bedrooms</option><option value="8">8 bedrooms</option><option value="9">9 bedrooms</option><option value="10">10 bedrooms</option>
                        <option value="11">11 bedrooms</option><option value="12">12 bedrooms</option><option value="13">13 bedrooms</option><option value="14">14 bedrooms</option><option value="15">15 bedrooms</option><option value="16">16 bedrooms</option><option value="17">17 bedrooms</option><option value="18">18 bedrooms</option><option value="19">19 bedrooms</option><option value="20">20 bedrooms</option>
                        <option value="21">21 bedrooms</option><option value="22">22 bedrooms</option><option value="23">23 bedrooms</option><option value="24">24 bedrooms</option><option value="25">25 bedrooms</option><option value="26">26 bedrooms</option><option value="27">27 bedrooms</option><option value="28">28 bedrooms</option><option value="29">29 bedrooms</option><option value="30">30 bedrooms</option>
                        <option value="31">31 bedrooms</option><option value="32">32 bedrooms</option><option value="33">33 bedrooms</option><option value="34">34 bedrooms</option><option value="35">35 bedrooms</option><option value="36">36 bedrooms</option><option value="37">37 bedrooms</option><option value="38">38 bedrooms</option><option value="39">39 bedrooms</option><option value="40">40 bedrooms</option>
                        <option value="41">41 bedrooms</option><option value="42">42 bedrooms</option><option value="43">43 bedrooms</option><option value="44">44 bedrooms</option><option value="45">45 bedrooms</option><option value="46">46 bedrooms</option><option value="47">47 bedrooms</option><option value="48">48 bedrooms</option><option value="49">49 bedrooms</option><option value="50">50 bedrooms</option>
                    </select>
                    <br/><br/><br/>
                    <label>How many beds can guests use?</label><br/>
                    <label>Total number of beds</label>
                    <i class="far fa-minus-square" style="font-size: 25px; margin-right: 20px; margin-left: 20px;"></i>
                    <input type="number" style="font-size: 20px; width: 60px;" id="numBeds" value="1" disabled name="numBeds">
                    <i class="far fa-plus-square" style="font-size: 25px; margin-left: 20px;"></i>
                    <br/><br/>
                    <label>Total number of bathrooms</label>
                    <i class="far fa-minus-square" style="font-size: 25px; margin-right: 20px; margin-left: 20px;"></i>
                    <input type="number" style="font-size: 20px; width: 60px;" id="numBathrooms" name="numBathrooms" value="1" disabled>
                    <i class="far fa-plus-square" style="font-size: 25px; margin-left: 20px;"></i>
                    <br/><br/>
                    <a href="host.php?step=1">
                        <button class="btn btn-default btn-lg">Back</button>
                    </a>
                    <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="accomodate" id="accomodate">
                </form>
                </div>
                <div class="col-sm-12 col-md-6">
                    <img src="img/host_2.jpg">
                </div>
            </div>
        </div>

        <style>
            .far.fa-plus-square, .far.fa-minus-square{
                cursor: pointer;
                color: black;
            }
            .far.fa-plus-square.disabled, .far.fa-minus-square.disabled{
                color: #b3b3b3;
                cursor: default;
            }
        </style>

        <script>
            var guests = document.getElementById('numGuests');
            var minus = document.getElementsByClassName('fa-minus-square')[0];
            var plus = document.getElementsByClassName('fa-plus-square')[0];

            if(!minus.classList.contains('disabled')) {
                minus.onclick = function() {
                    var x =  parseInt(guests.value);
                    if(x>1)
                        guests.value = x - 1;
                    if(guests.value == 1){
                        minus.classList.add('disabled');
                        plus.classList.remove('disabled');
                    } else if(guests.value == 16){
                        plus.classList.add('disabled');
                        minus.classList.remove('disabled');
                    } else {
                        minus.classList.remove('disabled');
                        plus.classList.remove('disabled');
                    }
                }
            }
            if(!plus.classList.contains('disabled')) {
                plus.onclick = function() {
                    var x =  parseInt(guests.value);
                    if(x<16)
                        guests.value = x + 1;
                    if(guests.value == 1){
                        guests.value = 1;
                        minus.classList.add('disabled');
                        plus.classList.remove('disabled');
                    } else if(guests.value ==16){
                        guests.value = 16;
                        plus.classList.add('disabled');
                        minus.classList.remove('disabled');
                    } else {
                        minus.classList.remove('disabled');
                        plus.classList.remove('disabled');
                    }
                }
            }
            guests.onchange = function(){
                if(guests.value == 1){
                    guests.value = 1;
                    minus.classList.add('disabled');
                    plus.classList.remove('disabled');
                } else if(guests.value == 16){
                    guests.value = 16;
                    plus.classList.add('disabled');
                    minus.classList.remove('disabled');
                } else {
                    minus.classList.remove('disabled');
                    plus.classList.remove('disabled');
                }
            }

            var beds = document.getElementById('numBeds');
            var minus1 = document.getElementsByClassName('fa-minus-square')[1];
            var plus1 = document.getElementsByClassName('fa-plus-square')[1];

            if(!minus1.classList.contains('disabled')) {
                minus1.onclick = function() {
                    var x =  parseInt(beds.value);
                    if(x>1)
                        beds.value = x - 1;
                    if(beds.value == 1){
                        minus1.classList.add('disabled');
                        plus1.classList.remove('disabled');
                    } else if(beds.value == 16){
                        plus1.classList.add('disabled');
                        minus1.classList.remove('disabled');
                    } else {
                        minus1.classList.remove('disabled');
                        plus1.classList.remove('disabled');
                    }
                }
            }
            if(!plus1.classList.contains('disabled')) {
                plus1.onclick = function() {
                    var x =  parseInt(beds.value);
                    if(x<50)
                        beds.value = x + 1;
                    if(beds.value == 1){
                        beds.value = 1;
                        minus1.classList.add('disabled');
                        plus1.classList.remove('disabled');
                    } else if(beds.value ==16){
                        beds.value = 16;
                        plus1.classList.add('disabled');
                        minus1.classList.remove('disabled');
                    } else {
                        minus1.classList.remove('disabled');
                        plus1.classList.remove('disabled');
                    }
                }
            }
            beds.onchange = function(){
                if(beds.value == 1){
                    beds.value = 1;
                    minus1.classList.add('disabled');
                    plus1.classList.remove('disabled');
                } else if(beds.value == 16){
                    beds.value = 16;
                    plus1.classList.add('disabled');
                    minus1.classList.remove('disabled');
                } else {
                    minus1.classList.remove('disabled');
                    plus1.classList.remove('disabled');
                }
            }

            var bathrooms = document.getElementById('numBathrooms');
            var minus2 = document.getElementsByClassName('fa-minus-square')[2];
            var plus2 = document.getElementsByClassName('fa-plus-square')[2];

            if(!minus2.classList.contains('disabled')) {
                minus2.onclick = function() {
                    var x =  parseInt(bathrooms.value);
                    if(x>1)
                        bathrooms.value = x - 1;
                    if(bathrooms.value == 1){
                        minus2.classList.add('disabled');
                        plus2.classList.remove('disabled');
                    } else if(bathrooms.value == 16){
                        plus2.classList.add('disabled');
                        minus2.classList.remove('disabled');
                    } else {
                        minus2.classList.remove('disabled');
                        plus2.classList.remove('disabled');
                    }
                }
            }
            if(!plus2.classList.contains('disabled')) {
                plus2.onclick = function() {
                    var x =  parseInt(bathrooms.value);
                    if(x<50)
                        bathrooms.value = x + 1;
                    if(bathrooms.value == 1){
                        bathrooms.value = 1;
                        minus2.classList.add('disabled');
                        plus2.classList.remove('disabled');
                    } else if(bathrooms.value ==16){
                        bathrooms.value = 16;
                        plus2.classList.add('disabled');
                        minus2.classList.remove('disabled');
                    } else {
                        minus2.classList.remove('disabled');
                        plus2.classList.remove('disabled');
                    }
                }
            }
            bathrooms.onchange = function(){
                if(bathrooms.value == 1){
                    bathrooms.value = 1;
                    minus2.classList.add('disabled');
                    plus2.classList.remove('disabled');
                } else if(bathrooms.value == 16){
                    bathrooms.value = 16;
                    plus2.classList.add('disabled');
                    minus2.classList.remove('disabled');
                } else {
                    minus2.classList.remove('disabled');
                    plus2.classList.remove('disabled');
                }
            }
        </script>

    <?php } ?>

    <?php
        if($step == 3) { 
            if(isset($_POST['accomodate'])) {
                $numGuests = $_POST['numGuests'];
                $numBeds = $_POST['numBeds'];
                $bedrooms = $_POST['bedrooms'];
                $bathrooms = $_POST['numBathrooms'];
            }
    ?>
        
        <div class="row">
            <div class="container">
                <div class="col-md-6 col-sm-12">
                    <div id="locationField">
                        <input id="autocomplete" placeholder="Enter your address"
                                onFocus="geolocate()" type="text" class="form-control"></input>
                    </div>
                    <br/><br/>
                    <form method="post" action="host.php?step=4">
                        <div class="form-group">
                            <label>Country/Region</label>
                            <input type="text" name="country" id="country" class="form-control" required placeholder="Country">
                        </div>
                        <div class="form-group">
                            <label>Street Address</label>
                            <input type="text" name="street" id="street_number" class="form-control" required placeholder="House Name/Number + Street/Road">
                        </div>
                        <div class="form-group">
                            <label>Apt, Suite</label>
                            <input type="text" name="suite" id="suite" class="form-control" placeholder="Apt., Suite, Building Access Code">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" id="locality" class="form-control" required placeholder="City">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" id="administrative_area_level_1" class="form-control" required placeholder="State">
                        </div>
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="number" name="zip" id="postal_code" class="form-control" required placeholder="Zip Code">
                        </div>
                        <a href="host.php?step=2">
                            <button class="btn btn-default btn-lg">Back</button>
                        </a>
                        <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="location" id="location">
                    </form>
                </div>
                <div class="col-md-6">
                    <img src="img/host_2.jpg">
                </div>
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
                /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
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
            document.getElementById(component).disabled = false;
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
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8oc4HbLoQUnqWaZr7SFnpvcLBSG7iEio&libraries=places&callback=initAutocomplete"
            async defer></script>

    <?php } ?>

    <?php
        if($step == 4) { 
            if(isset($_POST['location'])) {
                $country = $_POST['country'];
                $street = $_POST['street'];
                $suite = $_POST['suite'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $zip = $_POST['zip'];
            }
    ?>

            <div class="row">
                <div class="container">
                    <div class="col-sm-12 col-md-6">
                        <h3><b>What amenities do you offer?</b></h3>
                        <form method="post" action="host.php?step=5" style="font-size: 20px;">
                            <div class="checkbox">
                                <label><input type="checkbox" value="essentials" name="amenity[]">&nbsp;Essentials</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="wifi" name="amenity[]">&nbsp;Wifi</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="shampoo" name="amenity[]">&nbsp;Shampoo</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="closet" name="amenity[]">&nbsp;Closet/Drawers</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="tv" name="amenity[]">&nbsp;TV</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="heat" name="amenity[]">&nbsp;Heat</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="ac" name="amenity[]">&nbsp;Air Conditioning</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="breakfast" name="amenity[]">&nbsp;Breakfast, Coffee, Tea</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="workplace" name="amenity[]">&nbsp;Desk/WorkPlace</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="fireplace" name="amenity[]">&nbsp;FirePlace</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="iron" name="amenity[]">&nbsp;Iron</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="dryer" name="amenity[]">&nbsp;Hair Dryer</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="pets" name="amenity[]">&nbsp;Pets in House</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="entrance" name="amenity[]">&nbsp;Private Entrance</label>
                            </div>
                            <br/>
                            <h4><b>Safety Amenities</b></h4>
                            <div class="checkbox">
                                <label><input type="checkbox" value="smoke detector" name="safetyAmenity[]">&nbsp;Smoke Detector</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="CO detector" name="safetyAmenity[]">&nbsp;Carbon Monoxide Detector</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="first aid kit" name="safetyAmenity[]">&nbsp;First Aid Kit</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="safety card" name="safetyAmenity[]">&nbsp;Safety Card</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="fire extinguisher" name="safetyAmenity[]">&nbsp;Fire Extinguisher</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="lock" name="safetyAmenity[]">&nbsp;Lock on bedroom door</label>
                            </div>
                            <hr>
                            <a href="host.php?step=3">
                                <button class="btn btn-default btn-lg">Back</button>
                            </a>
                            <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="amenities" id="amenities">
                        </form>
                    </div>  
                    <div class="col-sm-12 col-md-6">
                        <img src="img/host_2.jpg">
                    </div>  
                </div>
            </div>

    <?php } ?>

    <?php
        if($step == 5) { 
            if(isset($_POST['amenities'])) {
                $amenity = $_POST['amenity'];
                $safety = $_POST['safetyAmenity'];
            }
    ?>

            <div class="row">
                <div class="container">
                    <div class="col-sm-12 col-md-6">
                        <h3><b>What spaces can guests use?</b></h3>
                        <form method="post" action="host.php?step=6" style="font-size: 20px;">
                            <div class="checkbox">
                                <label><input type="checkbox" value="pool" name="space[]">&nbsp;Pool</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="kitchen" name="space[]">&nbsp;Kitchen</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="washer" name="space[]">&nbsp;Laundry - washer</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="dryer" name="space[]">&nbsp;Laundry - dryer</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="parking" name="space[]">&nbsp;Parking</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="elevator" name="space[]">&nbsp;Elevator</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="tub" name="space[]">&nbsp;Hot Tub</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="gym" name="space[]">&nbsp;Gym</label>
                            </div>
                            <hr>
                            <a href="host.php?step=4">
                                <button class="btn btn-default btn-lg">Back</button>
                            </a>
                            <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="spaces" id="spaces">
                        </form>
                    </div>  
                    <div class="col-sm-12 col-md-6">
                        <img src="img/host_2.jpg">
                    </div>  
                </div>
            </div>

    <?php } ?>

    <?php
        if($step == 6) { 
            if(isset($_POST['spaces'])) {
                $space = $_POST['space'];
            }
    ?>  
        <form action="host.php?step=7" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="container">
                    <h3><b>Show travelers what your space looks like</b></h3>
                    <h4>Select all the four image</h4>
                    <div class="col-sm-3 col-md-3">
                        <div id="imageOne">
                            <input type="image" src="img/addImage.png" width="250px" data-num="one"/>
                            <input type='file' onchange="readURL(this);" id="my_file_one" style="display: none;" name="image[]" required/>
                            <img id="blah_one" src="#" style="display: none;"/> 
                            <br/>
                            <center> 
                                <button type="button" class="btn btn-default" id="remove" style="display: none;" data-num="one">Remove</button>
                            </center> 
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div id="imageTwo">
                            <input type="image" src="img/addImage.png" width="250px" data-num="two"/>
                            <input type='file' onchange="readURL(this);" id="my_file_two" style="display: none;" name="image[]" required/>
                            <img id="blah_two" src="#" style="display: none;"/>  
                            <br/>
                            <center> 
                                <button type="button" class="btn btn-default" id="remove" style="display: none;" data-num="two">Remove</button>
                            </center> 
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div id="imageThree">
                            <input type="image" src="img/addImage.png" width="250px" data-num="three"/>
                            <input type='file' onchange="readURL(this);" id="my_file_three" style="display: none;" name="image[]" required/>
                            <img id="blah_three" src="#" style="display: none;"/>  
                            <br/>
                            <center> 
                                <button type="button" class="btn btn-default" id="remove" style="display: none;" data-num="three">Remove</button>
                            </center> 
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div id="imageFour">
                            <input type="image" src="img/addImage.png" width="250px" data-num="four"/>
                            <input type='file' onchange="readURL(this);" id="my_file_four" style="display: none;" name="image[]" required/>
                            <img id="blah_four" src="#" style="display: none;"/>  
                            <br/>
                            <center> 
                                <button type="button" class="btn btn-default" id="remove" style="display: none;" data-num="four">Remove</button>
                            </center> 
                        </div>
                    </div>
                </div>
            </div>

            <br/><br/><br/>
            <div class="row">
                <div class="container">
                <hr>
                <a href="host.php?step=5">
                    <button class="btn btn-default btn-lg">Back</button>
                </a>
                <input type="submit" class="btn btn-default btn-lg" value="Next" style="float: right;" name="images" id="images">
                </div>
            </div>
        </form>
            
            <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
            <script>
                console.log($("input[type='image']"));
                $num = "";
                $("input[type='image']").click(function() {
                    $num = $(this).attr('data-num');
                    $("input[id='my_file_"+$num+"']").click();
                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $("input[data-num='"+$num+"']").hide();
                            $("button[data-num="+$num+"]").show();
                            $('#blah_'+$num).css('display', 'block');
                            $('#blah_'+$num)
                                .attr('src', e.target.result)
                                .width(250)
                                .height(250);
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }
                
                $("#remove").click(function() {
                    $num = $(this).attr('data-num');
                    $("button[data-num="+$num+"]").hide();
                    $('#blah_'+$num).hide();
                    console.log($num);
                    $("input[data-num='"+$num+"']").show();
                });
            </script>

    <?php } ?>

    <?php
        if($step == 7) { 
            if(isset($_POST['images'])) {
                var_dump($_POST);
                $images = $_POST['image'];
            }
    ?>

    <?php } ?>

    <?php 
        include 'includes/footer.php'; 
    ?>