<?php
session_start();
if(!isset($_SESSION['loggedUser'])){
  die ("<a href=login.php>Login</a> to view page");
}
$loggedUserID=intval($_SESSION['loggedUser']);

require_once ('src/post.php');
require_once ('src/user.php');
require_once ('conf/config.php');
$loggedUserName= User::loadUserByID($conn,$loggedUserID) ->getUsername();


if ($_SERVER['REQUEST_METHOD']=='POST'){
  if (isset($_POST['title']) && isset($_POST['content'])){
    if(!is_string($_POST['title']) || $_POST['title']==""){
      die ("invalid title <a href=mainpage.php>back to main page</a>");
    }
    if(!is_string($_POST['content']) || $_POST['content']==""){
      die ("invalid content <a href=mainpage.php>back to main page</a>");
    }
    $title=$_POST['title'];
    $content=$_POST['content'];

    $post = new Post;
    $post->setTitle($title);
    $post->setContent($content);
    $post->setAuthorID($loggedUserID);
    $post->savetoDB($conn);
  }
}
$allPosts = Post::loadAllPosts($conn);
 ?>
 <!doctype html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport"
           content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>MySQL Forum - Main</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
 </head>
 <body>
 <div class="container">
     <div class="row">
         <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
         <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <h1>Welcome <?php echo $loggedUserName; ?></h1>
              <?php echo "
              <a href=allMessages.php?id=$loggedUserID>Your Messages</a> &nbsp - &nbsp
              <a href=userDetails.php?id=$loggedUserID>Your Profile</a> &nbsp - &nbsp
              <a href=userEdit.php?id=$loggedUserID>Edit User Details</a> &nbsp - &nbsp
              <a href=allUsers.php>View All Users</a> &nbsp - &nbsp
              <a href=logout.php>Logout</a><br><br>
              ";
              ?>
             <legend>New Post</legend>
             <form action='' method='POST' role='form'>
                 Title:<br>
                 <input type='text' name='title'><br>
                 Content:<br>
                 <textarea name='content'style='height:300px;width:500px;'></textarea><br>
                 <button type='submit' class='btn btn-success'>Add Post</button><br><br>
             </form>
             <legend> All Posts</legend>
             <ul class='posts'>
             <?php
             foreach ($allPosts as $key=>$value){
                $title=$value->getTitle();
                $id=$value->getID();
                $authorId=$value->getAuthorId();
                $date=$value->getDate();
                $author = User::loadUserByID($conn,$authorId) ->getUsername();
                echo "<li class='posts'><a href=posts.php?id=$id&authorID=$authorId&authorName=$author>$title</a> posted by <a href=userDetails.php?id=$authorId>$author</a> on $date</li>";
             }
             $conn->close();
             $conn = null;
             ?>
             </ul>
         </div>
         <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
     </div>
 </div>

 </body>
 </html>
