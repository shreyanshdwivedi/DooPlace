<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require dirname(dirname(__FILE__)).'/vendor/autoload.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
    <link rel="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <title></title>
    <style>
        form .error{
            color: #ff6666;
        }
    </style>
</head>
<body>

<?php
    $conn = new mysqli("localhost", "root", "", "codingCampus");

    if(isset($_POST['resetPassword1'])) {
        $email = $_POST['email'];
        $type = "email";

        $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=? AND email=?");
        $stmt->bind_param("ss", $type, $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        if($user == null) {
            $_SESSION['error'] = "No such Email ID is registered!!";
            header("Location: 500.php");
        } else {   
                $digits = 7;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                try {
                    //Server settings
                    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'test22091997@gmail.com';                 // SMTP username
                    $mail->Password = 'Wireless031';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                                    // TCP port to connect to

                    //Recipients
                    $mail->setFrom('test22091997@gmail.com', 'DooPlace');
                    $mail->addAddress($email, "");     

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Reset your password';
                    $mail->Body    = 'OTP to reset your password is '.$otp.'<br/> 
                                      Please visit on this link to reset your password - http://localhost/desktop/Website-CodingCampus/resetPassword.php?section=otp&email='.$email;

                    $mail->send();

                    $stmt = $conn->prepare("SELECT * FROM resetPassword WHERE email=?");
                    $stmt->bind_param("s",$email);
                    $stmt->execute();
                    $stmt->store_result();
                    $num_rows = $stmt->num_rows;
                    $stmt->close();
                    if($num_rows > 0) {
                        $stmt = $conn->prepare("DELETE FROM resetPassword WHERE email = ?");
                        $stmt->bind_param("s",$email);
                        $result = $stmt->execute();
                        $stmt->close();

                        if($result) {   
                            $stmt = $conn->prepare("INSERT INTO resetPassword(email, otp) values(?, ?)");
                            $stmt->bind_param("ss", $email, $otp);
                            $result = $stmt->execute();
                            $stmt->close();
                            $_SESSION['success'] = "OTP sent successfully!";
                        } else {
                            $_SESSION['error'] = "There is an error!";
                        }
                    } else {
                        $stmt = $conn->prepare("INSERT INTO resetPassword(email, otp) values(?, ?)");
                        $stmt->bind_param("ss", $email, $otp);
                        $result = $stmt->execute();
                        $stmt->close();
                        $_SESSION['success'] = "OTP sent successfully!";
                    }
                    header("Location: ../resetPassword.php");
                } catch (Exception $e) {
                    $_SESSION['error'] = "There is an error in sending OTP!!";
                    header("Location: 500.php");
                }
        }
    } else if(isset($_POST['resetPassword2'])) {
        $email = $_POST['email'];
        $otp = $_POST['otp'];

        $stmt = $conn->prepare("SELECT * FROM resetPassword WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $obj = $stmt->get_result()->fetch_assoc();
        $stmt->close(); 

        if($obj['otp'] == $otp) {
?>

    <div class="row" style="margin-top: 25px;">
        <div class="container">
            <div class="col-md-3"></div>
            <div class="col-sm-12 col-md-6" style="border: 1px solid #eee; margin-top: 125px;">
                <h3 class="text-center">Change Your Password</h3>
                <h5 class="text-center"><?php echo $email; ?></h5><br/>
                <form method="post" action="" id="changePassword">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="chPassword">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="confirmPassword">
                    </div>
                    <input type="hidden" class="form-control" name="email" value="<?php echo $email; ?>">
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-success" value="Change Password" name="resetPassword3">
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
        } else {
            $_SESSION['error'] = "Please enter valid OTP!!";
            header("Location: ../resetPassword.php?section=otp&email=".$email);
        }
    } else if(isset($_POST['resetPassword3'])) {
        $pass = $_POST['password'];
        $password = md5($pass);
        $type = "email";
        $email = $_POST['email'];

        $stmt = $conn->prepare("DELETE FROM resetPassword WHERE email = ?");
        $stmt->bind_param("s",$email);
        $result = $stmt->execute();
        $stmt->close();

        if($result) {
            $stmt = $conn->prepare("UPDATE users SET `password` = ? WHERE `type`=? AND email=?");
            $stmt->bind_param("sss",$password, $type, $email);
            $result = $stmt->execute();
            $stmt->close();
        } else {
            $_SESSION['error'] = "There is an error!";
            header("Location: ../index.php");
        }

        if($result) {   
            $_SESSION['success'] = "Password changed successfully!";
            header("Location: ../index.php");
        } else {
            $_SESSION['error'] = "There is an error!";
            header("Location: ../index.php");
        }
    } else {
        header("Location: 500.php");
    }
?>

    <script src="../assets/js/jquery-1.11.1.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <script>
        $(function () {
                // Initialize form validation on the registration form.
                // It has the name attribute "registration"
                $("#changePassword").validate({
                    // Specify validation rules
                    rules: {
                        password: {
                            required: true,
                            minlength: 5
                        },
                        confirmPassword: {
                            equalTo: "#chPassword"
                        }
                    },
                    // Specify validation error messages
                    messages: {
                        password: {
                            required: "Please provide a password",
                            minlength: "Your password must be at least 5 characters long"
                        }
                    },
                        // Make sure the form is submitted to the destination defined
                        // in the "action" attribute of the form when valid
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
            });
        </script>
</body>
</html>