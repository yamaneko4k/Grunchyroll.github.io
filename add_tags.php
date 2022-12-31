<?php
session_start();
require 'php/database/config.php';
require 'php/string_generator.php';
require 'php/convert_size.php';
require 'php/database/traffic.php';

$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
  $page=1;
}
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}

$current_username = $_SESSION['username'];

$tag_err = $tag_succ = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if ($_POST["tag"])
    {   
        $tag = $_POST["tag"];

        $query = "SELECT * FROM tags WHERE tag_name = '$tag'";
        $result = mysqli_query($link,$query);

        if (mysqli_num_rows($result) > 0) {
            $tag_err  = 'Tag already exist';
        } else {
            $sql = "INSERT INTO tags (tag_name) VALUES ('$tag');";
            $execute = mysqli_query($link,$sql);
        
            if(!$execute){
                $tag_err  = "serveur error";
            }else{
                $tag_succ = "successfully uploaded";
            }
        }
    }
    else
    {
        $tag_err  = 'Enter a Tag';
    }  
}
?>

                          
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\edit_banner.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br>

    <div class="container">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <br>
        <h1>ADD TAGS</h1>
        <br>
        <label>ENTER TAG</label>
        <input type="text" name="tag" class="form-control">
        <span class="span_succ"><?php echo $tag_succ ?></span> 
        <span class="span_err"><?php echo $tag_err ?></span>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text"><strong>Upload</strong></span>
        </button>
      </form>
    </div>
  </body>
</html>