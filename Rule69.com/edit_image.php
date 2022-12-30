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


$image_title_err = '';

$current_username = $_SESSION['username'];
$image_to_edit = $_GET['image_to_edit'];
if (empty($image_to_edit)){header('location:profile.php?page='.$page.'');}
$_SESSION['image_to_edit'] = $image_to_edit;
$sql = "SELECT * FROM images WHERE id = '".$image_to_edit."' AND uploader_username = '".$current_username."'";
$rs_result=mysqli_query($link,$sql);
while($row = mysqli_fetch_array($rs_result)){
  $id = $row['id'];
  $image_title_display = $row["image_title"];
  $image = 'database/images/'.$row["file_name"];
}

if (isset($_SESSION['error_title']))
{
  $image_title_err = $_SESSION['error_title'];
  $_SESSION['error_title'] = '';
}
?>

                          
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\edit_image.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>
  
  <?php 
    $sql = "SELECT * FROM tags";
    $tags = mysqli_query($link,$sql);

    $recover_title = '';
    if (isset($_SESSION['recover_title_image'])){
      $recover_title = $_SESSION['recover_title_image'];
      $_SESSION['recover_title_image'] = '';
      if ($recover_title != ''){
        $image_title_display = $recover_title;
      }
    }
  ?>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br>

    <div class="container">
      <form action="php/database/update_image.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <br>
        <h1>EDIT IMAGE</h1>
        <br>
        <label>IMAGE TITLE (required)</label>
        <input type="text" name="image_title" class="form-control"value="<?php echo $image_title_display; ?>"> 
        <span class="span_err"><?php echo $image_title_err ?></span>
        <br>
        <!-- <label>Tags (recommended)</label>
        <select class="form-control" name="select" multiple multiselect-search="true" multiselect-max-items="2">
          <?php
            while($row = mysqli_fetch_array($tags)){
              $tag = $row["tag_name"];
          ?>
        <option><?php echo $tag; ?></option>
          <?php } ?>
        </select>
        <br><br> -->
        <div class="contain_img">
          <img loading="lazy" src="<?php echo $image ?>">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Current Image</label>
        </div>
        <br><br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text"><strong>Upload</strong></span>
        </button>
      </form>
      <br><br>
    </div>
  </body>
</html>