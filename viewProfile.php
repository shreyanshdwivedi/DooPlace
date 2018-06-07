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

        $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=? AND paramValue=?");
        $stmt->bind_param("ss", $_SESSION['loginType'], $_SESSION['paramValue']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 
    ?>

    <div class="row">
        <div class="container">
            <div class="col-md-3 col-sm-12">
                <img src="<?php if($user['image']!=""){echo $user['image'];}else{echo "img/user.jpg";}?>" alt="User Image">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Verified Info</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                            if($user['email_verified']){
                                echo 'Email ID <i class="far fa-check-circle" style="float: right;"></i>';
                            }
                            if($user['phone_verified']){
                                echo 'Phone Number <i class="far fa-check-circle" style="float: right;"></i>';
                            }
                            if(($user['email_verified'] == false) && ($user['phone_verified'] == false)) {
                                echo('No detail verified');
                            }
                        ?>
                    </div>
                </div>
                <br/>
                <?php if($user['bio']!=""){?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">About Me</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                                echo($user['bio']);
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-9 col-sm-12">
                <h2> Hey, I'm <?php echo $user['first_name']; ?> </h2>
                <h4><b>Joined In <?php echo date("F, Y", strtotime($user['timestamp']));?></b></h4>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>