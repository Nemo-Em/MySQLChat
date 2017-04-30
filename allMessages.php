<?php
session_start();
if(!isset($_SESSION['loggedUser'])){
  die ("<a href=login.php>Login</a> to view page");
}
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/user.php');
require_once ('src/message.php');
require_once ('conf/config.php');

$sql ="SELECT m.*, SUBSTRING_INDEX(content, ' ', 3) AS shortMessage, "
."ua.username AS sender, ua.id AS sender_id, "
."ur.username AS recipient, ur.id AS recipient_id, "
."m.id AS message_id FROM Messages m "
."JOIN Users ua ON m.author_id = ua.id "
."JOIN Users ur ON ur.id = m.recipient_id "
."WHERE author_id = $loggedUserID OR recipient_id= $loggedUserID";
$result= $conn->query($sql);
if($result==false){
  echo $conn->error;
}
$conn->close();
$conn = null;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySQL Forum - Messages</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <legend>Messages</legend>
            <b>Inbox</b>
            <ul class='messages'>
            <?php
            foreach ($result as $row){
              if ($row['recipient_id'] == $loggedUserID){
                $messageID = $row['message_id'];
                $sender = $row['sender'];
                $senderID = $row['sender_id'];
                echo "<li class='messages'><a href=message.php?id=$messageID>" . $row['shortMessage']
                . "...</a> from <a href=userDetails.php?id=$senderID>$sender</a> on " . $row['date'] . "</li>";
              }
            }
            ?>
            </ul>
            <b>Outbox</b>
            <ul class='messages'>
            <?php
            foreach ($result as $row){
              if ($row['author_id'] == $loggedUserID){
                $messageID = $row['message_id'];
                $recipient = $row['recipient'];
                $recipientID = $row['recipient_id'];
                echo "<li class='messages'><a href=message.php?id=$messageID>" . $row['shortMessage']
                . "...</a> to <a href=userDetails.php?id=$recipientID>$recipient</a> on " . $row['date'] . "</li>";
              }
            }
            ?>
            </ul>
            <br><br><a href=mainpage.php>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
