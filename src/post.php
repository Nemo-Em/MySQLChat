<?php
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

class Post {
private $id;
private $author_id;
private $title;
private $content;
private $date;

  public function __construct(){
    $this->id=-1;
    $this->author_id=0;
    $this->content=null;
    $this->title=null;
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
  public function getTitle(){
    return $this->title;
  }
  public function setTitle($text){
    if (is_string($text)){
      $this->title=$text;
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
  public function getDate(){
    return $this->date;
  }
  public function saveToDB(mysqli $conn){
    $sql = "INSERT INTO Posts (title, content, author_id) VALUES"
          . "('$this->title','$this->content',$this->author_id)";
    $result = $conn->query($sql);
    if ($result == true) {
      $this->id = $conn->insert_id;
      echo "post added";
      return true;
    }
    else {
      echo "post could not be added". $conn->error;;
      return false;
    }
  }
  static public function loadPostByID(mysqli $conn, $id){
    $sql = "SELECT * FROM Posts WHERE id=$id";
    $result = $conn->query($sql);
    if($result==true && $result->num_rows ==1){
      $row = $result->fetch_assoc();
      $loadedPost = new Post;
      $loadedPost->id = $row['id'];
      $loadedPost->title = $row['title'];
      $loadedPost->content = $row['content'];
      $loadedPost->date = $row['date'];
      return $loadedPost;
    }
    echo "no such post";
    return null;
  }
  static public function loadAllPosts(mysqli $conn){
    $sql = "SELECT * FROM Posts ORDER BY date DESC";
    $postTable = array();
    $result=$conn->query($sql);
    if ($result == true && $result->num_rows>0){
      foreach ($result as $row){
        $loadedPost = new Post();
        $loadedPost->id = $row['id'];
        $loadedPost->title = $row['title'];
        $loadedPost->content = $row['content'];
        $loadedPost->author_id = $row['author_id'];
        $loadedPost->date = $row['date'];
        $postTable[] = $loadedPost;
      }
    }
  return $postTable;
  }
}
