<?php
    session_start();
    if($_SESSION['isLoggedIn'] == false) {
        $_SESSION['error'] = "Please login first!";
        header("Location: index.php");
    }
?>    
    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php include 'includes/header.php'; ?>

    <?php include 'includes/navbar.php';?>

    <?php
        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $stmt = $conn->prepare("SELECT * FROM property WHERE userID=?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

        <div class="row">
            <div class="container">
                <h3 class="text-center">Your Places</h3>

    <?php
    if($result) {
        while($property = $result->fetch_assoc()) {
            $relatedTo = 'property';
            $stm = $conn->prepare("SELECT * FROM likes WHERE userID=? AND (relatedTo=? AND relatedID=?)");
            $stm->bind_param("sss", $_SESSION['userID'], $relatedTo, $property['id']);
            $stm->execute();
            $num = $stm->get_result()->num_rows;
            $stm->close();

                $relatedTo = 'property';
                $stm = $conn->prepare("SELECT * FROM images WHERE relatedTo=? AND relatedID=?");
                $stm->bind_param("ss", $relatedTo, $property['id']);
                $stm->execute();
                $image = $stm->get_result()->fetch_assoc();
                $stm->close();

                echo('<div class="col-sm-12 col-md-3">
                <div class="shop-card">
                <div class="shop-image">
                  <a href="property.php?id=');
                echo($property['id']);
                echo('">
                    <img src="'.$image['location'].'" height="160px" width="100%">
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
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #ff6666; cursor: pointer;" data-property-id="');
                  } else {
                      echo('<i class="far fa-heart" style="font-size: 20px; float: left; color: #737373; cursor: pointer;" data-property-id="');
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
                echo('</b> <span id="per">per night</span></div>');
                echo('<div class="starRate">
                  <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                </div>
              </div>
            </div>
            </div>');
        }
    } else {
        echo('<h3>You have yet not hosted. Try it out.. :)');
    }

    $stmt->close();
    ?>
            </div>
        </div>  

<?php include 'includes/footer.php'; ?>