<?php
class User {
private $id;
private $email;
private $username;
private $hashedPassword;

  public function __construct(){
    $this->id=-1;
    $this->email="";
    $this->username="";
    $this->hashedPassword = "";
  }
  public function getID(){
    return $this->id;
  }
  public function setID($id){
    if (is_integer($id) && $id>=0){
      $this->id=$id;
    }
  }
  public function getEmail(){
    return $this->email;
  }
  public function setEmail($email){
    if (is_string($email)){
      $this->email=$email;
    }
  }
  public function getUsername(){
    return $this->username;
  }
  public function setUsername($username){
    if (is_string($username)){
      $this->username=$username;
    }
  }
  public function setHashedPassword($password){
    $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
  }
  public function saveToDB(mysqli $conn){
    if($this->id== -1) {
      $sql = "INSERT INTO Users (email, username, hashed_password) VALUES"
            . "('$this->email','$this->username','$this->hashedPassword')";
      $result = $conn->query($sql);
      if ($result == true) {
        $this->id = $conn->insert_id;
        echo "new user added";
        return true;
      }
      if ($result == false){
        echo "could not create account" . $conn->error;
        return false;
      }
    }
    else{
      $sql = "UPDATE Users SET email = '$this->email', username = '$this->username', "
      ."hashed_password = '$this->hashedPassword' "
      ."WHERE id = $this->id";
      $result=$conn->query($sql);
      if ($result == true){
        echo "changed account details";
        return true;
      }
      if ($result == false){
        echo "could not make changes" . $conn->error;
        return false;
      }
    }
    return false;
  }

  public function deleteUser(mysqli $conn){
    if ($this->id !=-1){
      $sql = "DELETE FROM Users where id = $this->id";
      $result=$conn->query($sql);
      if ($result==true){
        $this->id =-1;
        return true;
      }
      else{
        return false;
      }
    }
  return true;
  }
  static public function loadUserByID(mysqli $conn, $id){
    $sql = "SELECT * FROM Users WHERE id=$id";
    $result = $conn->query($sql);
    if($result==true && $result->num_rows ==1){
      $row = $result->fetch_assoc();
      $loadedUser = new User;
      $loadedUser->id = $row['id'];
      $loadedUser->email = $row['email'];
      $loadedUser->username = $row['username'];
      $loadedUser->hashedPassword = $row['hashed_password'];

      return $loadedUser;
    }
    echo "no such user";
    return null;
  }
  static public function loadAllUsers(mysqli $conn){
    $sql = "SELECT * FROM Users ORDER BY username";
    $userTable = array();
    $result=$conn->query($sql);
    if ($result == true && $result->num_rows>0){
      foreach ($result as $row){
        $loadedUser = new User();
        $loadedUser->id = $row['id'];
        $loadedUser->email = $row['email'];
        $loadedUser->username = $row['username'];
        $loadedUser->hashedPassword = $row['hashed_password'];
        $userTable[] = $loadedUser;
      }
    }
    return $userTable;
  }
  public function login(){
    $_SESSION['loggedUser'] = $this->id;
  }
  static public function logout(){
    unset ($_SESSION['loggedUser']);
  }
}
