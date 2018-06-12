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
            $to = "";
        }
        $obj = new DbOperation(); 
    ?>

<div class="row">
    <div class="container">
        <div class="col-sm-4 col-md-3" style="overflow-y: scroll; padding-right: 20px;">
            <?php
                $users = $obj->getAllConversations($_SESSION['userID']);
                
                foreach($users as $userID) {
                    $conn = new mysqli("localhost", "root", "", "codingCampus");
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
                    $stmt->bind_param("s", $userID["id"]);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close(); 
            ?>
            <div class="row text-center" style="border: 1px solid #eee;">
                <a href="messages.php?to=<?php echo strtoupper($user['id']); ?>" style="color: #333;">
                    <div class="col-sm-4 col-md-6">
                        <img src="img/user.jpg" height="100px" width="100px" style="border-radius: 50%;">
                    </div>
                    <div class="col-sm-8 col-md-6" style="padding-top: 20px;">
                        <p><b><?php echo $user['first_name']." ".$user['last_name']; ?></b></p>
                        <p><i><?php echo strtoupper($user['gender']); ?></i></p>
                    </div>
                </a>
            </div>
            <br/>
            <?php
                }
            ?>
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="row">
                <div class="container text-center">
                    <div class="col-sm-12 col-md-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <img src="img/user.jpg" height="100px" width="100px" style="border-radius: 50%;">
                                </div>      
                            </div>
                            <div class="panel-body" style="height: 450px;">
                                <?php
                                    if($to == "") {
                                        echo('<h3>Select a user to view his/her messages</h3>');
                                    } else {
                                        $msgs = $obj->getMessageBetween($_SESSION['userID'], $to);
                                        foreach($msgs as $msg) {
                                            if($msg[2] == $_SESSION['userID']) {
                                                echo('<div style="float: right; background-color: #eee;">'.$msg[3].'<br/><span style="font-size: 10px;">'.$msg[8].'</span></div><br/><br/>');
                                            } else {
                                                echo('<div style="float: left; background-color: #fff;">'.$msg[3].'<br/><span style="font-size: 10px;">'.$msg[8].'</span></div><br/><br/>');
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            <?php
                                if($to != ""){
                            ?>
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
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>