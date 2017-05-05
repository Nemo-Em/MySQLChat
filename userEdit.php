<?php
session_start();
if(!isset($_SESSION['loggedUser'])){
  die ("<a href=login.php>Login</a> to view page");
}
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/user.php');
require_once ('conf/config.php');

$sql="SELECT hashed_password FROM Users WHERE id = $loggedUserID";
$result = $conn->query($sql);
if($result==false){
  echo $conn->error;
}
foreach($result as $row){
  $hashedPassword = $row['hashed_password'];
}
if ($_SERVER['REQUEST_METHOD']=='POST'){
  $user=User::loadUserByID($conn,$loggedUserID);
  if (isset($_POST['submitUsername'])){
    if (is_string($_POST['username']) && strlen($_POST['username'])>0 && strlen($_POST['username'])<=10){
      $password=$_POST['passwordUsername'];
      if (password_verify ($password , $hashedPassword)==false){
        die ("wrong password <a href=userEdit.php?id=$loggedUserID>Back</a>");
      }
      $username=$_POST['username'];
      $user->setUsername($username);
      }
    else {
      die("invalid username/username can't be more than 10 characters <a href=userEdit.php?id=$loggedUserID>Back</a>");
    }
  }
  if (isset($_POST['submitEmail'])){
    if (is_string($_POST['email']) && strpos($_POST['email'], '@')){
      $password=$_POST['passwordEmail'];
      if (password_verify ($password , $hashedPassword)==false){
        die ("wrong password <a href=userEdit.php?id=$loggedUserID>Back</a>");
      }
      $email=$_POST['email'];
      $user->setEmail($email);
    }
    else {
      die("invalid email <a href=userEdit.php?id=$loggedUserID>Back</a>");
    }
  }
  if (isset($_POST['submitPassword'])){
    if (is_string($_POST['newPassword']) && strlen($_POST['newPassword'])>=8){
      $password=$_POST['passwordOld'];
      $newPassword =$_POST['newPassword'];
      if (password_verify ($password , $hashedPassword)==false){
        die ("wrong password <a href=userEdit.php?id=$loggedUserID>Back</a>");
      }
      $user->setHashedPassword($newPassword);
      }
    else {
      die("invalid password/password must be at least 8 characters <a href=userEdit.php?id=$loggedUserID>Back</a>");
    }
  }
  $user->saveToDB($conn);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySQL Forum - User Edit</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action="" method="POST" role="form">
                <legend>Edit User</legend>
                <b>Change Username:</b><br>
                <input type="text" name="username" placeholder="username"> enter new username up to 10 chars<br>
                <input type="password" name="passwordUsername" placeholder="password"> enter password<br>
                <button type="submit" name= "submitUsername" class="btn btn-success">Change</button><br><hr>
                <b>Change Email:</b><br>
                <input type="text" name="email" placeholder="email"> enter new valid email<br>
                <input type="password" name="passwordEmail" placeholder="password"> enter password<br>
                <button type="submit" name="submitEmail" class="btn btn-success">Change</button><br><hr>
                <b>Change Password:</b><br>
                <input type="password" name="newPassword" placeholder="new password"> enter new password min 8 characters<br>
                <input type="password" name="passwordOld" placeholder="current password"> enter current password<br>
                <button type="submit" name="submitPassword" class="btn btn-success">Change</button>
            </form>
            <br><br>
            <a href=userDetails.php?id=<?php echo $loggedUserID; ?>>Your Profile</a> &nbsp - &nbsp
            <a href=mainpage.php>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
