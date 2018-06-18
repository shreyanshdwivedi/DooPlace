<?php
    session_start();
    include('way2sms-api.php');


    if(isset($_POST)) {
        $phoneNum = $_POST['countryCode'].$_POST['phoneNum'];
        $userID = $_SESSION['userID'];

        $digits = 7;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

        $msg = "Hello ".$_SESSION['name'].", OTP for your DooPlace mobile verification is ".$otp;

        sendWay2SMS ( '7080397532' , 'surajdubey' , '+91-8318799361' , $msg);  

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        $query = "SELECT * FROM phoneVerification WHERE userID=".$userID;
        if($stm = $conn->prepare($query)){
            $stm->execute();
            $stm->store_result();
            $num = $stm->num_rows;
            $stm->close();
        }
        if($num > 0) {
            $sql = "DELETE FROM phoneVerification WHERE userID=".$userID;
            $conn->query($sql);
        }
            
        $stmt = $conn->prepare("INSERT INTO phoneVerification(userID, otp) values(?, ?)");
        $stmt->bind_param("ss", $userID, $otp);
        $result = $stmt->execute();
        $stmt->close();

        // return 1;
    }
?>