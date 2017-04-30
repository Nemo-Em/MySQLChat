<?php
session_start();
require_once ('src/user.php');
require_once ('conf/config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])){
  $email= $_POST['email'];
  $password = $_POST['password'];
  $sql="SELECT * FROM Users WHERE email='$email'";
  $result=$conn->query($sql);
  if ($result == false){
    die ("email address doesn't exist <a href=login.php>try again</a>");
  }
  elseif ($result== true){
    foreach ($result as $row){
      if (password_verify ($password , $row['hashed_password'])==false){
        die ("wrong password <a href=login.php>try again</a>");
      }
      $newUser = new User();
      $newUser-> setID (intval($row['id']));
      $newUser-> setEmail($row['email']);
      $newUser-> setUsername($row['username']);
      $conn->close();
      $conn=null;
      $newUser->login();
    }
  }
  if (isset ($_SESSION['loggedUser'])){
    header("location:mainpage.php");
  }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySQL Forum - Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action="" method="POST" role="form">
                <legend>Login</legend>
                Email:<br>
                <input type="text" name="email"><br>
                Password:<br>
                <input type="password" name="password">
                <button type="submit" class="btn btn-success">Login</button>
            </form>
            Don't have an account? <a href=register.php>Register</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
