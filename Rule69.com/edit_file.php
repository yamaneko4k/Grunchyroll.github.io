<?php
session_start();
require 'php/database/config.php';
require 'php/string_generator.php';
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
  $page=1;
}
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}

$file_title_err = '';
$file_thumbnail_err = '';

$current_username = $_SESSION['username'];
$file_to_edit = $_GET['file_to_edit'];
if (empty($file_to_edit)){header('location:profile.php?page='.$page.'');}
$_SESSION['file_to_edit'] = $file_to_edit;
$sql = "SELECT * FROM files WHERE id = '".$file_to_edit."' AND file_creator_username = '".$current_username."'";
$rs_result=mysqli_query($link,$sql);
while($row = mysqli_fetch_array($rs_result)){
  $id = $row['id'];
  $file_title_display = $row["file_title"];
  $thumbnail = 'database/files/thumbnails/'.$row["thumbnail"];
}

if (isset($_SESSION['error_title']))
{
  $file_title_err = $_SESSION['error_title'];
  $_SESSION['error_title'] = '';
}

if (isset($_SESSION['error_thumbnail']))
{
  $file_thumbnail_err = $_SESSION['error_thumbnail'];
  $_SESSION['error_thumbnail'] = '';
}
?>

                          
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\edit_file.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>
  
  <?php 
    $recover_title = '';
    if (isset($_SESSION['recover_title_file'])){
      $recover_title = $_SESSION['recover_title_file'];
      $_SESSION['recover_title_file'] = '';
      if ($recover_title != ''){
        $file_title_display = $recover_title;
      }
    }
  ?>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br>

    <div class="container">
      <form action="php/database/update_file.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <br>
        <h1>EDIT FILE</h1>
        <br>
        <label>FILE TITLE (required)</label>
        <input type="text" name="file_title" class="form-control"value="<?php echo $file_title_display; ?>"> 
        <span class="span_err"><?php echo $file_title_err ?></span>
        <br>
        <div class="contain_img">
          <img loading="lazy" src="<?php echo $thumbnail ?>">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Current Thumbnail</label>
        </div>
        <br><br><br>
        <label>THUMBNAIL (optional) <b class="nice"> *you already have one*</b></label>
        <input type="file" name="file_thumbnail" accept="image/*" class="form-control" value="<?php echo $file_thumbnail; ?>">
        <span class="span_err"><?php echo $file_thumbnail_err ?></span>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text"><strong>Upload</strong></span>
        </button>
      </form>
      <br><br>
    </div>
  </body>
</html>