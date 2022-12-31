<?php
session_start();
require 'php/database/config.php';
require 'php/convert_time.php';
require 'php/database/staff.php';

if (isset($_GET['search'])){
  $_SESSION['search'] = $_GET['search'];
}
if (isset($_GET['select'])){
  $_SESSION['select'] = $_GET['select'];
}
?>
                               
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\index.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <link rel="stylesheet" href="css\video.css">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\pagination.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background">
    <?php require 'php/loader.php'; ?>
    <?php require 'php/nav_bar.php'; ?>
    <br><br>
    <div class="container">
      <form action="" method="GET" enctype="multipart/form-data" name="upload-form">
      <div class="form-group">
        <br>
        <h1>Search</h1>
        <br>
        <input type="text" name="search" class="form-control" value="<?php if (isset($_SESSION['search'])) {echo $_SESSION['search'];}?>" placeholder="search">
        <br>
        <select class="form-control" name="select" value="dsds">
        <?php if (isset($_SESSION['select']) && $_SESSION['select'] == 'Videos'){ echo '<option>Videos</option>'; } if (isset($_SESSION['select']) && $_SESSION['select'] == 'Images'){ echo '<option>Images</option>'; } if (isset($_SESSION['select']) && $_SESSION['select'] == 'Posts'){ echo '<option>Posts</option>'; } ?>
        <?php if (isset($_SESSION['select']) && $_SESSION['select'] == 'Videos'){}else{ echo '<option>Videos</option>';} ?>
        <?php if (isset($_SESSION['select']) && $_SESSION['select'] == 'Images'){}else{ echo '<option>Images</option>';} ?>
        <?php if (isset($_SESSION['select']) && $_SESSION['select'] == 'Posts'){}else{ echo '<option>Posts</option>';} ?>
        </select>
        <br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text">Search</span>
        </button>
        <br>
        <br>
      </div>
    </div>

    <?php 
    if (isset($_GET['select']))
    {
      if ($_GET['select'] == 'Videos'){     
        if (isset($_GET['search']) && $_GET['search'] != ''){
          $search = $_GET['search'];
          $query = "SELECT * FROM videos WHERE CONCAT(uploader_username,video_title) LIKE '%$search%'";
          $search_result = mysqli_query($link,$query);
          if (mysqli_num_rows($search_result) > 0){ ?>
            <div class="item">
               <div class="order"> 
                  <?php foreach($search_result as $result){ ?>
                    <div class="crop_img">   
                      <?php echo "<a title=".$result["uploader_username"]." href=\"display_video.php?id={$result['id']}\"</a>"; ?><img  id="img01" src="<?php echo 'database/thumbnails/'.$result["thumbnail_name"]; ?>" loading="lazy" ></a>
                      <p class="img_time"><?php echo $result["video_lenght"]; ?></p> 
                      <p class="img_Title"><strong><?php echo $result["video_title"]; ?></strong></p>  
                      <?php 
                      if(in_array($result["uploader_username"], $verified_accounts)){
                        if(in_array($result["uploader_username"], $owner_accounts)){
                          echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$result["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                        }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$result["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                        } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$result["uploader_username"].'</p>'; }
                      ?>
                      <p class="img_Description"><?php echo number_format($result["view_count"]); ?> views · <?php echo (time_elapsed_string($result['uploaded_on']));?></p>
                  </div>
            <?php
            }
          }
        }
      }if ($_GET['select'] == 'Images'){
        if (isset($_GET['search']) && $_GET['search'] != ''){
          $search = $_GET['search'];
          $query = "SELECT * FROM images WHERE CONCAT(uploader_username,image_title) LIKE '%$search%' ";
          $search_result = mysqli_query($link,$query);
          if (mysqli_num_rows($search_result) > 0){ ?>
            <div class="item">
               <div class="order"> 
                  <?php foreach($search_result as $result){ ?>
                    <div class="crop_img">   
                      <?php echo "<a title=".$result["uploader_username"]." href=\"display_image.php?id={$result['id']}\"</a>"; ?><img  id="img01" src="<?php echo 'database/images/'.$result["file_name"]; ?>" loading="lazy" ></a>
                      <br><br>
                      <p class="img_Title"><strong><?php echo $result["image_title"]; ?></strong></p>  
                      <?php 
                      if(in_array($result["uploader_username"], $verified_accounts)){
                        if(in_array($result["uploader_username"], $owner_accounts)){
                          echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$result["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                        }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$result["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                        } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$result["uploader_username"].'</p>'; }
                      ?>
                      <p class="img_Description"><?php echo number_format($result["view_count"]); ?> views · <?php echo (time_elapsed_string($result['uploaded_on']));?></p>
                  </div>
            <?php
            }
          }
        }
      }if ($_GET['select'] == 'Posts'){
        if (isset($_GET['search']) && $_GET['search'] != ''){
          $search = $_GET['search'];
          $query = "SELECT * FROM blogs WHERE CONCAT(blog_title,blog_content,blog_creator) LIKE '%$search%' ";
          $search_result = mysqli_query($link,$query);
          if (mysqli_num_rows($search_result) > 0){ ?>
            <div class="item">
               <div class="order"> 
                  <?php foreach($search_result as $result){ ?>
                    <div class="crop_img">   
                      <?php echo "<a title=".$result["blog_creator"]." href=\"display_image.php?id={$result['id']}\"</a>"; ?><img  id="img01" src="<?php echo 'database/blogs/thumbnails/'.$result["thumbnail"]; ?>" loading="lazy" ></a>
                      <br><br>
                      <p class="img_Title"><strong><?php echo $result["blog_title"]; ?></strong></p>  
                      <?php 
                      if(in_array($result["blog_creator"], $verified_accounts)){
                        if(in_array($result["blog_creator"], $owner_accounts)){
                          echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$result["blog_creator"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                        }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$result["blog_creator"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                        } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$result["blog_creator"].'</p>'; }
                      ?>
                      <p class="img_Description"><?php echo (time_elapsed_string($result['blog_creation_date']));?></p>
                  </div>
            <?php
            }
          }
        }
      }
    } 
    ?>
  </body>
</html>