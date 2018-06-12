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

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM likes WHERE userID=?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <div class="row">
        <div class="container">

    <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $stm = $conn->prepare("SELECT * FROM property WHERE id=?");
                $stm->bind_param("s", $row['propertyID']);
                $stm->execute();
                $property = $stm->get_result()->fetch_assoc();
                $stm->close();

                $stm = $conn->prepare("SELECT * FROM likes WHERE userID=? AND propertyID=?");
                $stm->bind_param("ss", $_SESSION['userID'], $row['propertyID']);
                $stm->execute();
                $num = $stm->get_result()->num_rows;
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
                  <a href="property.php?id=');
                  echo($property['id']);
            echo('">
                    <img src="img/test1.png">
                  </a>
                </div>
                <div class="shop-content">
                  <div id="name">
                    <div style="width: 90%; float: left;"> 
                      <b>');
                    echo(strtoupper($property['propertyType']));
            echo('</b>
                    </div>');

                  if($num>0) {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-property-id="');
                  }
            echo($property['id']);
                    echo('"></i>
                  </div>
                  <div id="detail"><b>
                    <a href="property.php?id=');
                      echo($property['id'].'" style="color: #333;">');
                    echo($property['name']);
            echo('</b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($property['perHourRate']);
            echo('</b> <span id="per">P/Hr</span></div>
                  <div id="perRate"><span id="per">Rs</span> <b>');
                  echo($property['perDayRate']);
            echo('</b> <span id="per">P/Day</span></div>
                  <div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> Excellent
                  </div>
                </div>
              </div>
              </div>');
            }
            $stmt->close();
        } else {
            echo('<h3>Nothing Liked yet? Start liking now!!</h3>');
        }
    ?>

        </div>
    </div>

    <?php
        include 'includes/footer.php';
    ?>