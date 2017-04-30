<?php
session_start();
if ($_SERVER['REQUEST_METHOD']=='POST'){
  require_once ('src/user.php');
  require_once ('conf/config.php');

  if (is_string($_POST['username']) && strlen($_POST['username'])>0 && strlen($_POST['username'])<=10){
      $username=$_POST["username"];
    }
  else {
    die("invalid username/username can't be more than 10 characters <a href=register.php>try again</a>");
  }

  if (is_string($_POST['email']) && strpos($_POST['email'], '@')){
    $email=$_POST['email'];
  }
  else {
    die("invalid email <a href=register.php>try again</a>");
  }
  if (is_string($_POST['password']) && strlen($_POST['password'])>=8){
    $password = $_POST['password'];
    }
  else {
    die("invalid password/password must be at least 8 characters <a href=register.php>try again</a>");
  }

  $newUser = new User();
  $newUser-> setEmail("$email");
  $newUser-> setUsername("$username");
  $newUser-> setHashedPassword("$password");
  $register = $newUser->saveToDB($conn);
  $conn->close();
  $conn=null;
  if($register == true){
    $newUser->login();
  }
  elseif ($register == false){
    die ("email address already taken <a href=register.php>try again</a>");
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
    <title>MySQL Forum - Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action="" method="POST" role="form">
                <legend>New User</legend>
                <b>Select Username:</b><br>
                <input type="text" name="username"> enter username up to 10 chars<br>
                <b>Email:</b><br>
                <input type="text" name="email"> enter valid email<br>
                <b>Password:</b><br>
                <input type="password" name="password"> enter password min 8 characters<br>
                <button type="submit" class="btn btn-success">Register</button>
            </form>
            Already have an account? <a href=login.php>Login</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>
