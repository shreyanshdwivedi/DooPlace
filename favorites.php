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

        $relatedTo = 'property';
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM likes WHERE userID=? AND relatedTo=?");
        $stmt->bind_param("ss", $_SESSION['userID'], $relatedTo);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <div class="row">
        <div class="container">
            <h3 class="text-center">Your Favorites :)</h3><br/>

    <?php
        if ($result->num_rows > 0) {
            echo('<h4 class="text-center"><b>Houses liked by you</b></h4><br/>');
            while($row = $result->fetch_assoc()) {
                $stm = $conn->prepare("SELECT * FROM property WHERE id=?");
                $stm->bind_param("s", $row['relatedID']);
                $stm->execute();
                $property = $stm->get_result()->fetch_assoc();
                $stm->close();

                $stm = $conn->prepare("SELECT * FROM likes WHERE userID=? AND (relatedID=? AND relatedTo=?)");
                $stm->bind_param("sss", $_SESSION['userID'], $row['relatedID'], $relatedTo);
                $stm->execute();
                $num = $stm->get_result()->num_rows;
                $stm->close();

                $relatedTo = 'property';
                $stm = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
                $stm->bind_param("ss", $relatedTo, $row['relatedID']);
                $stm->execute();
                $image = $stm->get_result()->fetch_assoc();
                $stm->close();

                // $query = "SELECT * FROM likes WHERE userID=".$_SESSION['userID']." AND propertyID=".$property['id'];
                // if($stm = $conn->prepare($query)){
                //     $stm->execute();
                //     $stm->store_result();
                //     $num = $stm->num_rows;
                //     $stm->close();
                // }
                
                echo('<div class="col-sm-12 col-md-3">
                  <div class="shop-card">
                  <div class="shop-image">
                  <a href="restaurant.php?id=');
                  echo($property['id']);
                  echo('">
                    <img src="'.$image["location"].'" height="160px" width="100%">
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
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="restaurant" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="restaurant" data-property-id="');
                  }
                  echo($property['id']);
                    echo('"></i>
                  </div>
                  <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
                    <a href="restaurant.php?id=');
                      echo($property['id'].'" style="color: #333;">');
                    echo($property['name']);
                  echo('</a></b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($property['perDayRate']);
                  echo('</b> <span id="per">per person</span></div>');
                  echo('<div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                  </div>
                </div>
              </div>
              </div>');
            }
            $stmt->close();
        } else {
            echo('<h5 class="text-center">Nothing Liked yet? Start liking now!!</h5>');
        }
    ?>

        </div>
    </div><br/>

    <div class="row">
        <div class="container">
            <img src="https://a0.muscache.com/4ea/air/v2/pictures/5b64bcbf-d6b1-4cbf-b866-9b5f91027b35.jpg?t=c:w1131-h343,r:w1131-h343-sfit,e:fjpg-c75" width="100%" style="border-radius: 10px;">
        </div>
    </div><br/><br/><br/><br/>

    <?php 
        $relatedTo = 'restaurant';
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM likes WHERE userID=? AND relatedTo=?");
        $stmt->bind_param("ss", $_SESSION['userID'], $relatedTo);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <div class="row">
        <div class="container">

    <?php
        if ($result->num_rows > 0) {
            echo('<h4 class="text-center"><b>Restaurants liked by you</b></h4><br/>');
            while($row = $result->fetch_assoc()) {
                $stm = $conn->prepare("SELECT * FROM restaurants WHERE id=?");
                $stm->bind_param("s", $row['relatedID']);
                $stm->execute();
                $restaurant = $stm->get_result()->fetch_assoc();
                $stm->close();

                $stm = $conn->prepare("SELECT * FROM likes WHERE userID=? AND (relatedID=? AND relatedTo=?)");
                $stm->bind_param("sss", $_SESSION['userID'], $row['relatedID'], $relatedTo);
                $stm->execute();
                $num = $stm->get_result()->num_rows;
                $stm->close();

                $relatedTo = 'restaurant';
                $stm = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
                $stm->bind_param("ss", $relatedTo, $row['relatedID']);
                $stm->execute();
                $image = $stm->get_result()->fetch_assoc();
                $stm->close();

                // $query = "SELECT * FROM likes WHERE userID=".$_SESSION['userID']." AND propertyID=".$property['id'];
                // if($stm = $conn->prepare($query)){
                //     $stm->execute();
                //     $stm->store_result();
                //     $num = $stm->num_rows;
                //     $stm->close();
                // }
                
                echo('<div class="col-sm-12 col-md-3">
                  <div class="shop-card">
                  <div class="shop-image">
                  <a href="restaurant.php?id=');
                  echo($restaurant['id']);
                  echo('">
                    <img src="'.$image["location"].'" height="160px">
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
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-type="restaurant" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-type="restaurant" data-property-id="');
                  }
                  echo($restaurant['id']);
                    echo('"></i>
                  </div>
                  <div id="detail" style="height: 75px !important; overflow-x: ellipsis;"><b>
                    <a href="restaurant.php?id=');
                      echo($restaurant['id'].'" style="color: #333;">');
                    echo($restaurant['name']);
                  echo('</a></b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($restaurant['price']);
                  echo('</b> <span id="per">per person</span></div>');
                  echo('<div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                  </div>
                </div>
              </div>
              </div>');
            }
            $stmt->close();
        }
    ?>

        </div>
    </div>

    <?php
        include 'includes/footer.php';
    ?>