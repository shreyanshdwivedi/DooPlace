<?php 
        session_start(); 
        if(!($_SESSION['isLoggedIn'] == true)) {
            header('Location: index.html');
        }    
    ?>   

    <?php 
        require_once 'includes/messaging.php';
        include 'includes/fb-login.php'; 
    ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php 
        include 'includes/header.php'; 
        include 'includes/navbar.php';

        if(isset($_GET['to'])) {
            $to = $_GET['to'];
        } else {
            $_SESSION['error'] = "Enter a valid URL";
            header('Location: index.php');
        }
        $obj = new DbOperation(); 
    ?>

        <div class="row">
            <div class="container text-center">
                <h3>Messaging</h3>
                <div class="col-md-2"></div>
                <div class="col-sm-12 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <img src="img/user.jpg" height="100px" width="100px" style="border-radius: 50%;">
                            </div>      
                        </div>
                        <div class="panel-body" style="height: 450px;">
                            <?php
                                $msgs = $obj->getMessageBetween($_SESSION['userID'], $to);
                                foreach($msgs as $msg) {
                                    if($msg[2] == $_SESSION['userID']) {
                                        echo('<div style="float: right; background-color: #eee;">'.$msg[3].'<br/><span style="font-size: 10px;">'.$msg[8].'</span></div><br/><br/>');
                                    } else {
                                        echo('<div style="float: left; background-color: #fff;">'.$msg[3].'<br/><span style="font-size: 10px;">'.$msg[8].'</span></div><br/><br/>');
                                    }
                                }
                            ?>
                        </div>
                        <div class="row">
                                <form action="includes/send.php" method="post">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="msg">
                                        <input type="hidden" class="form-control" name="user_to" value="<?php echo $to; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" class="btn btn-default" value="Send" style="width: 100%;">
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>

    <?php
        include 'includes/footer.php';
    ?>
