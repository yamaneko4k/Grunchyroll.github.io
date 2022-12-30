<?php
session_start();
require 'php/database/config.php';
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
  $page=1;
}
?>
                               
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link type="text/css" rel="stylesheet" href="css\delete.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background">

    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>
    <div class="container">
      <div class="form-group">
        <br><br><br>
        <h1>ARE YOU SURE ?</h1>
        <br>

        <?php if(isset($_GET['image_to_delete'])) { 
        $current_username = $_SESSION['username'];
        $image_to_delete = $_GET['image_to_delete'];
        if (empty($image_to_delete)){header('location:profile.php?page='.$page.'');}
        $_SESSION['image_to_delete'] = $image_to_delete;
        $sql = "SELECT * FROM images WHERE id = '".$image_to_delete."' AND uploader_username = '".$current_username."'";
        $rs_result=mysqli_query($link,$sql);
        while($row = mysqli_fetch_array($rs_result)){
            $id = $row['id'];
            $thumbnail = 'database/images/'.$row["file_name"];
        }
        ?>
        <label>DELETE IMAGE ?</label><br> <?php } ?>

        <?php if(isset($_GET['video_to_delete'])) { $current_username = $_SESSION['username'];
        $video_to_delete = $_GET['video_to_delete'];
        if (empty($video_to_delete)){header('location:profile.php?page='.$page.'');}
        $_SESSION['video_to_delete'] = $video_to_delete;
        $sql = "SELECT * FROM videos WHERE id = '".$video_to_delete."' AND uploader_username = '".$current_username."'";
        $rs_result=mysqli_query($link,$sql);
        while($row = mysqli_fetch_array($rs_result)){
            $id = $row['id'];
            $thumbnail = 'database/thumbnails/'.$row["thumbnail_name"];
        }
        ?>
        <label>DELETE VIDEO ?</label>
        <?php } ?>

        <?php if(isset($_GET['blog_to_delete'])) { $current_username = $_SESSION['username'];
        $blog_to_delete = $_GET['blog_to_delete'];
        if (empty($blog_to_delete)){header('location:profile.php?page='.$page.'');}
        $_SESSION['blog_to_delete'] = $blog_to_delete;
        $sql = "SELECT * FROM blogs WHERE id = '".$blog_to_delete."' AND blog_creator = '".$current_username."'";
        $rs_result=mysqli_query($link,$sql);
        while($row = mysqli_fetch_array($rs_result)){
            $id = $row['id'];
            $thumbnail = 'database/blogs/thumbnails/'.$row["thumbnail"];
        }
        ?>
        <label>DELETE BLOG ?</label>
        <?php } ?>

        <?php if(isset($_GET['file_to_delete'])) { $current_username = $_SESSION['username'];
        $file_to_delete = $_GET['file_to_delete'];
        if (empty($file_to_delete)){header('location:profile.php?page='.$page.'');}
        $_SESSION['file_to_delete'] = $file_to_delete;
        $sql = "SELECT * FROM files WHERE id = '".$file_to_delete."' AND file_creator_username = '".$current_username."'";
        $rs_result=mysqli_query($link,$sql);
        while($row = mysqli_fetch_array($rs_result)){
            $id = $row['id'];
            $thumbnail = 'database/files/thumbnails/'.$row["thumbnail"];
            $file = 'database/files/content/'.$row["file_name"];
        }
        ?>
        <label>DELETE FILE ?</label>
        <?php } ?>

        <div class="contain_img">
          <img loading="lazy" src="<?php echo $thumbnail ?>">
        </div>
        <br><br><br>
        <a href="php/database/delete_logic.php"><input type="submit" name="submit" class="btn" value="DELETE"></a>
        <a href="profile.php?page=<?php echo $page ?>"><input type="submit" style="background-color:green;float:left"  name="submit" class="btn" value="RETURN"></a>
      <br><br>
    </div>

  </body>
</html>