<?php
 session_start();
 if(!isset($_SESSION['loggedUser'])){
   die ("<a href=login.php>Login</a> to view page");
 }
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/user.php');
require_once ('src/post.php');
require_once ('src/comment.php');
require_once ('src/message.php');
require_once ('conf/config.php');

if(!isset($_GET['id']) || $_GET['id'] ==""){
  die ("no user selected <a href=mainpage.php>back to main page</a>");
}
$userId=intval($_GET['id']);

$sql = "select p.*, COUNT(c.id) as count, p.id as post_id, p.date as post_date, u.username as username, u.email as email FROM Posts p "
."LEFT JOIN Comments c on c.post_id=p.id JOIN Users u on p.author_id = u.id WHERE p.author_id = $userId "
."GROUP BY p.id ORDER BY post_date DESC";
$result= $conn->query($sql);
if($result==false){
  echo $conn->error;
}
$sql2 = "SELECT * FROM Users where id = $userId";
$result2= $conn->query($sql2);
if($result2==false){
  echo $conn->error;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
  if(!is_string($_POST['content']) || $_POST['content']==""){
    die ("invalid content <a href=userDetails.php?id=$userId>back to user</a>");
  }
  if (isset($_POST['content'])){
    $content=$_POST['content'];
    $message = new Message;
    $message->setContent($content);
    $message->setAuthorID($loggedUserID);
    $message->setRecipientID($userId);
    $message->savetoDB($conn);
   }
 }

$conn->close();
$conn=null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySQL Forum - User Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <legend>User Details</legend>
            <?php
            foreach ($result2 as $row){
              $userID = $row['id'];
              $username = $row['username'];
              $email = $row['email'];
            }
            echo "<b>User account:</b> $userID <b>Name:</b> $username <b>Email:</b> $email<hr>";
            ?>
            <legend>User Posts</legend>
            <ul class='posts'>
            <?php
            $postCount=0;
            foreach ($result as $row){
              $postID = $row['post_id'];
              $postCount++;
              echo "<b><a href=posts.php?id=$postID&authorID=$userID&authorName=$username>" . $row['title'] . "</a></b> posted on: " . $row['post_date'] . "<br>" . $row['count'] . " comments <br><hr>";
            }
            if ($postCount==0){
              echo "this user has no posts!";
            }
            ?>
            </ul>
            <legend>Message User</legend>
            <form action='' method='POST' role='form'>
                Message:<br>
                <textarea name='content'style='height:200px;width:500px;'></textarea><br>
                <button type='submit' class='btn btn-success'>Message</button>
            </form>
            <br><br><a href=mainpage.php>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
