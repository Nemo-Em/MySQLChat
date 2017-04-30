<?php
class Comment {
private $id;
private $author_id;
private $post_id;
private $content;
private $date;

  public function __construct(){
    $this->id=-1;
    $this->author_id=0;
    $this->post_id=0;
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
  public function setPostID($num){
    if (is_integer($num)){
      $this->post_id=$num;
    }
  }
  public function saveToDB(mysqli $conn){
    $sql = "INSERT INTO Comments (content, author_id, post_id) VALUES"
          . "('$this->content',$this->author_id,$this->post_id)";
    $result = $conn->query($sql);
    if ($result == true) {
      $this->id = $conn->insert_id;
      echo "comment added";
      return true;
    }
    else {
      echo "comment could not be added" . $conn->error;
      return false;
    }
  }

}
