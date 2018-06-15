<?php
    session_start();
?>    
    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php include 'includes/header.php'; ?>

    <?php include 'includes/navbar.php';?>

    <?php
        $section = "";
        if(isset($_GET['section'])) {
            $section = $_GET['section'];
        }

        if($section == "") {
    ?>
    <div class="row" style="margin-top: 25px;">
        <div class="container">
            <div class="col-md-3"></div>
            <div class="col-sm-12 col-md-6" style="border: 1px solid #eee;">
                <h3 class="text-center">Reset Password</h3><br/>
                <form method="post" action="includes/resetPassword.php">
                    <div class="form-group">
                        <label>Email ID</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-danger" value="Send OTP" name="resetPassword1">
                    </div>
                </form>
                <p class="text-center" style="color:#ff6666;">An OTP will be sent on this Email ID. Make sure you have access to this.</p> 
            </div>
        </div>
    </div>

<?php
    } else if($section == "otp") {
        if(isset($_GET['email'])) {
            $email = $_GET['email'];
        } else {
            $_SESSION['error'] = "Invalid Link";
            header("Location: includes/500.php");
        }
?>

    <div class="row" style="margin-top: 25px;">
        <div class="container">
            <div class="col-md-3"></div>
            <div class="col-sm-12 col-md-6" style="border: 1px solid #eee;">
                <h3 class="text-center">Reset Password</h3>
                <h5 class="text-center"><?php echo $email; ?></h5><br/>
                <form method="post" action="includes/resetPassword.php">
                    <div class="form-group">
                        <label>OTP</label>
                        <input type="number" class="form-control" name="otp">
                        <input type="hidden" class="form-control" name="email" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-success" value="Confirm" name="resetPassword2">
                    </div>
                </form>
                <p class="text-center" style="color:#4cae4c;">Enter the OTP sent to your email</p> 
            </div>
        </div>
    </div>

<?php } include 'includes/footer.php';?>