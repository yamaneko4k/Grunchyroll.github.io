<?php
session_start();
require 'php/database/config.php';
require 'php/convert_time.php';
require 'php/database/staff.php';


if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php");
}
$num_per_page=10;
if (isset($_GET["page"])){
  $page=$_GET["page"];
}else{
  $page=1;
}
$start_from=($page-1)*$num_per_page;

$current_username = $_SESSION['username'];


$sql = "SELECT * FROM images WHERE uploader_username = '$current_username' ORDER BY uploaded_on DESC limit $start_from,$num_per_page";
$rs_result=mysqli_query($link,$sql);

$sql2 = "SELECT * FROM videos WHERE uploader_username = '$current_username' ORDER BY uploaded_on DESC limit $start_from,$num_per_page";
$rs_result2=mysqli_query($link,$sql2);

$sql3 = "SELECT * FROM blogs WHERE blog_creator = '$current_username' ORDER BY blog_creation_date DESC limit $start_from,$num_per_page";
$rs_result3=mysqli_query($link,$sql3);

$sql4 = "SELECT * FROM files WHERE file_creator_username = '$current_username' ORDER BY creation_date DESC limit $start_from,$num_per_page";
$rs_result4=mysqli_query($link,$sql4);

$stat_sql4 = "SELECT * FROM files WHERE file_creator_username = '$current_username'";
$stat_rs_result4=mysqli_query($link,$stat_sql4);
$stat_total_records4=mysqli_num_rows($stat_rs_result4);
$stat_total_pages4=ceil($stat_total_records4/$num_per_page);

$stat_sql3 = "SELECT * FROM blogs WHERE blog_creator = '$current_username'";
$stat_rs_result3=mysqli_query($link,$stat_sql3);
$stat_total_records3=mysqli_num_rows($stat_rs_result3);
$stat_total_pages3=ceil($stat_total_records3/$num_per_page);

$stat_sql2 = "SELECT * FROM videos WHERE uploader_username = '$current_username'";
$stat_rs_result2=mysqli_query($link,$stat_sql2);
$stat_total_records2=mysqli_num_rows($stat_rs_result2);
$stat_total_pages2=ceil($stat_total_records2/$num_per_page);

$stat_sql = "SELECT * FROM images WHERE uploader_username = '$current_username'";
$stat_rs_result=mysqli_query($link,$stat_sql);
$stat_total_records=mysqli_num_rows($stat_rs_result);
$stat_total_pages=ceil($stat_total_records/$num_per_page);


$user_stat_sql = "SELECT created_at FROM users WHERE username = '$current_username'";
$user_sql_result = mysqli_query($link,$user_stat_sql);
$finder = mysqli_fetch_array($user_sql_result);
$date = $finder["created_at"];
$timestamp = strtotime($date);

$image_total = $stat_total_records;
$video_total = $stat_total_records2;
$blog_total = $stat_total_records3;
$file_total = $stat_total_records4;
$overall_upload = $stat_total_records + $stat_total_records2 + $stat_total_records3 + $stat_total_records4;
?>                    
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\pagination.css">
    <link rel="stylesheet" href="css\profile.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background_img">
    <?php require 'php/loader.php'; ?>
    <?php require 'php/nav_bar.php'; ?>
    <div class="wrapper">
      <?php echo '<p><strong>Welcome ' . $_SESSION["username"] . ' :)</strong></p>'; ?>
      <?php echo '<p><strong>Images: ' . $image_total. '</strong></p>'; ?>
      <?php echo '<p><strong>Videos: ' . $video_total . '</strong></p>'; ?>
      <?php echo '<p><strong>Blogs: ' . $blog_total . '</strong></p>'; ?>
      <?php echo '<p><strong>Files: ' . $file_total . '</strong></p>'; ?>
      <?php echo '<p><strong>Total: ' . $overall_upload . '</strong></p>'; ?>
      <?php echo '<p><strong>You Are a Member since ' . date('m/d/Y', $timestamp) . '</strong></p>'; ?>
      <?php echo '<p><strong>Which Is ' . time_elapsed_string($date) . '</strong></p>'; ?>
    </div> 
    <p class="text1"><strong>Your Uploads</strong></p>
    <div class="profile-option">
      <button type="submit" name="submit" onclick="location.href='edit_banner.php'" class="button" > 
      <span class="button__text"><strong>Edit</strong></span>
      </button>
      <!-- <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
          <span class="button__text"><strong>Picture</strong></span>
      </button> -->
    </div> 
    <div class="item">
      <div class="order">
          <?php 
            if(mysqli_num_rows($rs_result) < 1 && $page < 2){
              echo '<p class="p2">Images</p>';
              echo "<p><strong>No Images...</strong></p>";}
            if (mysqli_num_rows($rs_result) >= 1){
              echo '<p class="p2">Images</p>';
            }
          ?>
          <?php
              while($row = mysqli_fetch_array($rs_result)){
                $image_id = $row["id"];
                $imageURL = 'database/images/'.$row["file_name"];
                $time_ago = (time_elapsed_string($row['uploaded_on']));
                ?>
            <div class="crop_img">   
              <?php echo "<a title=".$row["uploader_username"]." href=\"display_image.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $imageURL; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_Title"><strong><?php echo $row["image_title"]; ?></strong></p> 
              <?php 
              if(in_array($row["uploader_username"], $verified_accounts)){
                if(in_array($row["uploader_username"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["uploader_username"].'</p>'; }
              ?>
              <p class="img_Description"><?php echo number_format($row["view_count"]); ?> views · <?php echo $time_ago;?></p>
              <a class="option" href="delete.php?image_to_delete=<?php echo $image_id ?>">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <a class="option" href="edit_image.php?image_to_edit=<?php echo $image_id  ?>">Editor</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <!-- <a href='<?php echo $imageURL ?>'>full size</a>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <?php 
              // devlog
              // echo $row["id"];
              ?>
              
              <!-- <a href=''>edit</a> -->
            </div>
            <?php } ?>
            <?php 
              $sql = "SELECT * FROM images WHERE uploader_username = '$current_username'";
              $rs_result=mysqli_query($link,$sql);
              $total_records=mysqli_num_rows($rs_result);
              $total_pages=ceil($total_records/$num_per_page);
            ?>

            <?php 
            if(mysqli_num_rows($rs_result2) < 1 && $page < 2){
              echo '<p class="p2">Videos</p>';
              echo "<p><strong>No Videos...</strong></p>";}
            if (mysqli_num_rows($rs_result2) >= 1){
              echo '<p class="p2">Videos</p>';
            }
            ?>
            <?php
               while($row = mysqli_fetch_array($rs_result2)){
                $video_id = $row["id"];
                $videoURL = 'database/thumbnails/'.$row["thumbnail_name"];
                ?>

            <div class="crop_img">   
              <?php echo "<a title=".$row["uploader_username"]." href=\"display_video.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $videoURL; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_time_video"><?php echo $row["video_lenght"]; ?></p>
              <p class="img_Title_video"><strong><?php echo $row["video_title"]; ?></strong></p>  
              <?php 
              if(in_array($row["uploader_username"], $verified_accounts)){
                if(in_array($row["uploader_username"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["uploader_username"].'</p>'; }
              ?>
              <p class="img_Description_video"><?php echo number_format($row["view_count"]); ?> views · <?php echo (time_elapsed_string($row['uploaded_on']));?></p>
              <a class="option" href="delete.php?video_to_delete=<?php echo $row["id"] ?>">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <a class="option" href="edit_video.php?video_to_edit=<?php echo $video_id  ?>">Editor</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <!-- <a href='<?php echo $videoURL ?>'>full size</a>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <?php 
              // devlog
              // echo $row["id"]
              ?>
            </div>
            <?php } ?>
            <?php 
              $sql2 = "SELECT * FROM videos WHERE uploader_username = '$current_username'";
              $rs_result2=mysqli_query($link,$sql2);
              $total_records2=mysqli_num_rows($rs_result2);
              $total_pages2=ceil($total_records2/$num_per_page);
            ?>

          <?php 
            if(mysqli_num_rows($rs_result3) < 1 && $page < 2){
              echo '<p class="p2">Blogs</p>';
              echo "<p><strong>No Blogs..</strong></p>";}
            if (mysqli_num_rows($rs_result3) >= 1){
              echo '<p class="p2">Blogs</p>';
            }
          ?>
          <?php
              while($row = mysqli_fetch_array($rs_result3)){
                $blog_id = $row["id"];
                $blog_thumbnail = 'database/blogs/thumbnails/'.$row["thumbnail"];
                $time_ago = (time_elapsed_string($row['blog_creation_date']));
                ?>
            <div class="crop_img">   
              <?php echo "<a title=".$row["blog_creator"]." href=\"display_blog.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $blog_thumbnail; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_Title"><strong><?php echo $row["blog_title"]; ?></strong></p> 
              <?php 
              if(in_array($row["blog_creator"], $verified_accounts)){
                if(in_array($row["blog_creator"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["blog_creator"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["blog_creator"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["blog_creator"].'</p>'; }
              ?>
              <p class="img_Description"><?php echo $time_ago;?></p>
              <a class="option" href="delete.php?blog_to_delete=<?php echo $blog_id ?>">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <a class="option" href="blog_edit.php?blog_to_edit=<?php echo $blog_id ?>">Editor</a>&nbsp;&nbsp;&nbsp;&nbsp;
              
              <!-- <a href='<?php echo $blog_thumbnail ?>'>full size</a>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <?php 
              // devlog
              // echo $row["id"];
              ?>
              
              <!-- <a href=''>edit</a> -->
            </div>
            <?php } ?>
            <?php 
              $sql3 = "SELECT * FROM blogs WHERE blog_creator = '$current_username'";
              $rs_result3=mysqli_query($link,$sql3);
              $total_records3=mysqli_num_rows($rs_result3);
              $total_pages3=ceil($total_records3/$num_per_page);
            ?>

          <?php 
            if(mysqli_num_rows($rs_result4) < 1 && $page < 2){
              echo '<p class="p2">Files</p>';
              echo "<p><strong>No Files..</strong></p>";}
            if (mysqli_num_rows($rs_result4) >= 1){
              echo '<p class="p2">Files</p>';
            }
          ?>
          <?php
              while($row = mysqli_fetch_array($rs_result4)){
                $file_id = $row["id"];
                $file_thumbnail = 'database/files/thumbnails/'.$row["thumbnail"];
                $time_ago = (time_elapsed_string($row['creation_date']));
                ?>
            <div class="crop_img">   
              <?php echo "<a title=".$row["file_creator_username"]." href=\"display_file.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $file_thumbnail; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_Title"><strong><?php echo $row["file_title"]; ?></strong></p> 
              <?php 
              if(in_array($row["file_creator_username"], $verified_accounts)){
                if(in_array($row["file_creator_username"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["file_creator_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["file_creator_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["file_creator_username"].'</p>'; }
              ?>
              <p class="img_Description"><?php echo $time_ago;?></p>
              <a class="option" href="delete.php?file_to_delete=<?php echo $file_id ?>">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp; 
              <a class="option" href="edit_file.php?file_to_edit=<?php echo $file_id ?>">Editor</a>&nbsp;&nbsp;&nbsp;&nbsp;
              
              <!-- <a href='<?php echo $file_thumbnail ?>'>full size</a>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <?php 
              // devlog
              // echo $row["id"];
              ?>
              
              <!-- <a href=''>edit</a> -->
            </div>
            <?php } ?>
            <?php 
              $sql4 = "SELECT * FROM files WHERE file_creator_username = '$current_username'";
              $rs_result4=mysqli_query($link,$sql4);
              $total_records4=mysqli_num_rows($rs_result4);
              $total_pages4=ceil($total_records4/$num_per_page);
            ?>
          </div>
        </div>

    <div class="center">
      <div class="pagination">
        
      <?php 
      if($total_pages > $total_pages2 && $total_pages > $total_pages3 && $total_pages > $total_pages4){
        $constant = $total_pages;
      } elseif ($total_pages2 > $total_pages && $total_pages2 > $total_pages3 && $total_pages2 > $total_pages4){
        $constant =  $total_pages2;
      } elseif ($total_pages3 > $total_pages && $total_pages3 > $total_pages && $total_pages3 > $total_pages4){
        $constant =  $total_pages3;
      }elseif ($total_pages4 > $total_pages && $total_pages4 > $total_pages2 && $total_pages4 > $total_pages3){
        $constant =  $total_pages4;
      }

      if (!empty($constant))
      {
          if (ceil($constant / $num_per_page) > 0): ?>
            <?php if ($page > 1): ?>
            <a href="profile.php?page=<?php echo $page-1 ?>">Prev</a>
            <?php else: ?>
              <a href="profile.php?page=<?php echo $page=1 ?>">Prev</a>
            <?php endif; ?>
                
            <?php 
            // if ($total_pages <= 5){
            //   for ($i=1;$i<=$total_pages;$i++)
            //   {
            //     echo "<a class='pagination' href='image.php?page=".$i."'>".$i."/ ".$total_pages."</a>";
            //   }
            // }else{
            //   // echo "<a class='pagination' href='image.php?page=",1,"'>1</a>"; 
            //   echo "<a class='pagination' href='image.php?page=".$page."'>".$page." / ".$total_pages."</a>"; 
            //   // echo "<a class='pagination' href='image.php?page=".$total_pages."'>".$total_pages."</a>";
            // }
            echo "<a class='pagination' href='profile.php?page=".$page."'>".$page." / ".$constant."</a>"; 
            $_SESSION['profile_current_page'] = $page;
            ?>

    
            <?php if ($page >= $constant): ?>
              <a class='active' href="profile.php?page=<?php echo $page=$constant ?>">Next</a>
            <?php else: ?>
              <a href="profile.php?page=<?php echo $page+1 ?>">Next</a>
            <?php endif; ?>     
          </div>
        </div>
        <?php endif; 
        if(mysqli_num_rows($rs_result) > 0){
          // echo "<a style='color: white' class='pagingNumbers'>".$page.'/'.$constant."</a>";
        }
        $_SESSION['Total Page'] = $constant;
      }
      $sql = "SELECT * FROM users WHERE username = '$current_username'";
      $result=mysqli_query($link,$sql);
      while($row = mysqli_fetch_array($result)){
        $URL = 'database/users/banners/'.$row["banner_name"];
      }
    ?>
    
  </body>

  <style>
  .background_img{
      background-image: url(<?php echo $URL ?>);
      background-attachment: fixed;
      background-repeat: no-repeat, repeat;
      -webkit-background-size: cover;
      -moz-background-size: contain;
      -o-background-size: contain;
      background-size: cover;
  }
  </style>
</html>
