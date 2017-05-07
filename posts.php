<?php
 session_start();
 require_once ('src/post.php');
 require_once ('src/comment.php');
 require_once ('src/user.php');
 require_once ('conf/config.php');

 if(!isset($_SESSION['loggedUser'])){
   die ("<a href=login.php>Login</a> to view page");
 }
 $loggedUserID=intval($_SESSION['loggedUser']);

if (!isset($_GET['id'])){
  die ("no post selected or post doesn't exist <a href=mainpage.php>back to main page</a>");
}
if(isset($_GET['id'])){
$postID=intval($_GET['id']);
$post=Post::loadPostByID($conn, $postID);
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
  if(!is_string($_POST['content']) || $_POST['content']==""){
    die ("invalid content <a href=posts.php?id=$postID>back to post</a>");
  }
  if (isset($_POST['content'])){
    $content=$_POST['content'];
    $comment = new Comment;
    $comment->setContent($content);
    $comment->setAuthorID($loggedUserID);
    $comment->setPostID($postID);
    $comment->savetoDB($conn);
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
      <title>MySQL Forum - Post</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  </head>
  <body>
  <div class="container">
      <div class="row">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
               <?php
               $author = $_GET['authorName'];
               $authorId= $_GET['authorID'];
               echo "<h2>" . $post->getTitle() . " by <a href=userDetails.php?id=$authorId>$author</a></h2>" . "<p>"
               . $post->getContent() . "</p>"
               ."<legend>Comments</legend>";
               $sql2 = "SELECT *, u.id AS user_id, c.content AS comment_content FROM Posts p"
               ." JOIN Comments c ON c.post_id = p.id"
               ." JOIN Users u ON c.author_id=u.id WHERE p.id = $postID";
               $result = $conn->query($sql2);
               $conn->close();
               $conn=null;
              $commentCount=0;
               foreach($result as $row){
                 $content = $row['comment_content'];
                 $commentAuthor = $row['username'];
                 $authorId = $row['user_id'];
                 $commentCount++;
                 echo "$content <br> posted by <a href=userDetails.php?id=$authorId>$commentAuthor</a><br><hr>";
               }
               if ($commentCount==0){
                 echo "no comments yet!";
               }
               ?>
              <legend>Add New Comment</legend>
              <form action='' method='POST' role='form'>
                  <textarea name='content'style='height:200px;width:500px;'></textarea><br>
                  <button type='submit' class='btn btn-success'>Add Comment</button>
              </form>
              <br><br>
              <a href=mainpage.php>Back to main page</a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
      </div>
  </div>

  </body>
  </html>
