<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require dirname(dirname(__FILE__)).'/vendor/autoload.php';

    if(isset($_POST['register'])) {

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $pass = $_POST['password'];
        $password = md5($pass);
        $verified = false;
        $apikey = md5(uniqid(rand(), true));
        $loginType = "email";
        $val = "email";

        $conn = new mysqli("localhost", "root", "", "codingCampus");

        //Returning 0 means student created successfully
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();

        if(!($num_rows > 0)) {
            $stmt = $conn->prepare("INSERT INTO users(`type`, `param`, `paramValue`, `first_name`, `last_name`, `email`, `dob`, `password`, `email_verified`, `apikey`) 
                    values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $loginType, $val, $email, $first_name, $last_name, $email, $dob, $password, $verified, $apikey);
            $result = $stmt->execute();
            $stmt->close();

            if ($result) {

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
                    $mail->addAddress($email, $first_name);     

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Verify';
                    $mail->Body    = 'Please verify your account by visiting this link - http://localhost/desktop/Website-CodingCampus/verifyEmail.php?type=email&email='.$email.'&key='.$apikey;

                    $mail->send();

                    $type = "email";
                    $stmt = $conn->prepare("SELECT * FROM users WHERE `type`=?, email=?, `password`=?");
                    $stmt->bind_param("sss", $type, $email,$password);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close(); 

                    $_SESSION['success'] = "Verification mail sent successfully";
                    $_SESSION['paramValue'] = $user['email'];
                    $_SESSION['userID'] = $user['id'];
                    $_SESSION['image'] = $user['image'];
                    $_SESSION['name'] = $user['first_name']." ".$user['last_name'];
                    $_SESSION['loginType'] = "email";
                    $_SESSION['isLoggedIn'] = true;
                    header("Location: ../index.php");
                } catch (Exception $e) {
                    $_SESSION['error'] = "There is an error in sending OTP!!";
                    header("Location: 500.php");
                }
            } else {
                $_SESSION['error'] = "There is an error in creating user!!";
                header("Location: 500.php");
            }
        } else {
            // echo '<script>
            //         alert("Email-ID already registered!!");
            //     </script>';
            // header("Location: ../index.php");
            $_SESSION['error'] = "Email-ID already registered!!";
            header("Location: 500.php");
        }
    }
?>