<?php
    session_start();
    require_once 'messaging.php';

    if(isset($_POST)) {
        $to = $_POST['user_to'];
        $from = $_SESSION['userID'];
        $msg = $_POST['msg'];

        $obj = new DbOperation();

        $result = $obj->sendMessage($from, $to, $msg);

        if(!$result){
            $_SESSION['success'] = "Message sent successfully";
        } else {
            $_SESSION['error'] = "Message sending failed";
        }
        header("Location: ../messaging.php?to=".$to);
    }
    

?>