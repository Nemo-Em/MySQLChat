<?php
session_start();
require_once ('src/user.php');
User::logout();
if (isset($_SESSION['loggedUser'])){
  echo "problem logging out";
}
if (!isset ($_SESSION['loggedUser'])){
  header("location:login.php");
}
