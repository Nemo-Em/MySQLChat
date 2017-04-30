<?php
session_start();
if(!isset($_SESSION['loggedUser'])){
  die ("<a href=login.php>Login</a> to view page");
}
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/user.php');
require_once ('src/message.php');
require_once ('conf/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  if (!isset($_GET['id']) || $_GET['id'] == 0){
    die ("message doesn't exist <a href=allMessages.php?id=$loggedUserID>Back to Messages</a>");
  }

  $sql ="SELECT m.*,"
  ."m.id AS message_id, ua.username AS sender, "
  ."ur.username AS recipient FROM Messages m "
  ." JOIN Users ua ON m.author_id=ua.id JOIN Users ur ON ur.id = m.recipient_id WHERE m.id =". $_GET['id'];
  $result= $conn->query($sql);
  if($result==false){
    echo $conn->error;
  }
  foreach ($result as $row){
    $messageID = $row['id'];
  }
  $sql2="UPDATE Messages SET is_read=1 WHERE id=$messageID";
  $result2= $conn->query($sql2);
  if($result2==false){
    echo $conn->error;
  }
  $conn->close();
  $conn = null;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySQL Forum - Read Message</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?php
            foreach ($result as $row){
              echo "<b>From:</b> " . $row['sender'] . "<b> To:</b> " . $row['recipient'] . "<br>"
              . "sent on: " . $row['date'] . "<hr>"
              . $row['content'];
            }
            ?>
            <br><br><a href=mainpage.php>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
