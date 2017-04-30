<?php
session_start();
if(!isset($_SESSION['loggedUser'])){
  die ("<a href=login.php>Login</a> to view page");
}
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/user.php');
require_once ('conf/config.php');

$allUsers = User::loadAllUsers($conn);
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
    <title>MySQL Forum - Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <legend> All Users</legend>
            <ul class='users'>
            <?php
            foreach ($allUsers as $key=>$value){
               $username=$value->getUsername();
               $id=$value->getID();
               echo "<li class='users'><a href=userDetails.php?id=$id>$username</a></li>";
            }
            ?>
            </ul>
            <hr>
            <br><br><a href=mainpage.php>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
