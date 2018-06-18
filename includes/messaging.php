<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
require dirname(dirname(__FILE__)).'/vendor/autoload.php';

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

                $stmt = $this->con->prepare("SELECT * FROM users WHERE id=?");
                $stmt->bind_param("s",$recipient);
                $stmt->execute();
                //Getting the student result array
                $user_to = $stmt->get_result()->fetch_all();
                $stmt->close();

                if($user_to['email'] != "") {
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try {
                        //Server settings
                        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                        $mail->isSMTP();                                      // Set mailer to use SMTP
                        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                               // Enable SMTP authentication
                        $mail->Username = 'test22091997@gmail.com';                 // SMTP username
                        $mail->Password = 'Wireless031';                           // SMTP password
                        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 465;                                    // TCP port to connect to

                        //Recipients
                        $mail->setFrom('test22091997@gmail.com', 'DooPlace');
                        $mail->addAddress($user_to['email'], $user_to['first_name']);     

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Message Received';
                        $mail->Body    = "Hello ".$user_to['first_name']."<br/>".$_SESSION['name']." sent a message to you.
                                        \"".$message."\"";

                        $mail->send();

                        $_SESSION['success'] = "Mail sent successfully";
                    } catch (Exception $e) {
                        $_SESSION['error'] = "There is an error in sending mail!!";
                    }
                }       
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