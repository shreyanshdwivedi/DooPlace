<?php

class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/dbconnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }

    public function sendMessage($sender, $recipient, $message){
        // if($this->isUserExists($sender, "")) {
        //     if($this->isUserExists($recipient, "")) {
                $stmt = $this->con->prepare("INSERT INTO messages(user_from, user_to, `message`) VALUES(?, ?, ?)");
                $stmt->bind_param("sss",$sender,$recipient,$message);
                $result = $stmt->execute();
                $stmt->close();

                if($result){
                    return 0;
                } else {
                    return 1;
                }
        //     } else {
        //         return 2;
        //     }
        // } else {
        //     return 3;
        // }
    }

    public function getMessageBetween($firstUser, $secondUser){
        // if($this->isUserExists($firstUser, "")) {
        //     if($this->isUserExists($secondUser, "")) {
                $stmt = $this->con->prepare("SELECT * FROM messages WHERE (user_to = ? AND user_from = ?) OR (user_to = ? AND user_from = ?)");
                $stmt->bind_param("ssss",$firstUser, $secondUser, $secondUser, $firstUser);
                $stmt->execute();
                //Getting the student result array
                $messages = $stmt->get_result()->fetch_all();
                $stmt->close();
                return $messages;
        //     } else {
        //         return 1;
        //     }
        // } else {
        //     return 2;
        // }
    }

    public function getAllConversations($userID){
        // if($this->isUserExists($firstUser, "")) {
        //     if($this->isUserExists($secondUser, "")) {
                $conn = new mysqli("localhost", "root", "", "codingCampus");
                $sql = "SELECT DISTINCT users.id FROM users INNER JOIN messages ON (users.id=messages.user_to AND messages.user_from=$userID) OR (users.id=messages.user_from AND messages.user_to=$userID)";
                $result = $conn->query($sql);
                return $result;
        //     } else {
        //         return 1;
        //     }
        // } else {
        //     return 2;
        // }
    }

    public function getAllMsg($userID){
        $stmt = $this->con->prepare("SELECT * FROM messages WHERE user_to = ? OR user_from = ?");
        $stmt->bind_param("ss",$userID, $userID);
        $stmt->execute();
        $messages = $stmt->get_result()->fetch_all();
        $stmt->close();
        return $messages;
    }
}