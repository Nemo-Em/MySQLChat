<?php
class Message {
private $id;
private $author_id;
private $recipient_id;
private $content;
private $date;

  public function __construct(){
    $this->id=-1;
    $this->author_id=0;
    $this->recipient_id=0;
    $this->content="";
  }
  public function getId(){
    return $this->id;
  }
  public function getContent(){
    return $this->content;
  }
  public function setContent($text){
    if (is_string($text)){
      $this->content=$text;
    }
  }
  public function getAuthorID(){
    return $this->author_id;
  }
  public function setAuthorID($num){
    if (is_integer($num)){
      $this->author_id=$num;
    }
  }
  public function getRecipientID(){
    return $this->recipient_id;
  }
  public function setRecipientID($num){
    if (is_integer($num)){
      $this->recipient_id=$num;
    }
  }
  public function saveToDB(mysqli $conn){
    $sql = "INSERT INTO Messages (content, author_id, recipient_id) VALUES"
          . "('$this->content',$this->author_id,$this->recipient_id)";
    $result = $conn->query($sql);
    if ($result == true) {
      $this->id = $conn->insert_id;
      echo "message sent";
      return true;
    }
    else {
      echo "message could not be sent" . $conn->error;
      return false;
    }
  }

}
