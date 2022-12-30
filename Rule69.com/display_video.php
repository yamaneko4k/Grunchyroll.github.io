<?php
session_start();
require 'php/database/config.php';
require 'php/convert_time.php';
require 'php/database/staff.php';

if(isset($_GET['id'])){
  $_SESSION['videoToDisplay']=$_GET['id'];
}

$VideoID = $_SESSION['videoToDisplay'];

$sql_search = "SELECT file_name,uploaded_on,uploader_username,view_count,video_title,thumbnail_name FROM videos WHERE id=$VideoID";
$sql_search_result = mysqli_query($link,$sql_search);
$find_elements = mysqli_fetch_array($sql_search_result);
$VideoToDisplayURL = 'database/videos/'.$find_elements["file_name"];
$VideoToDisplayThumbnails = 'database/thumbnails/'.$find_elements["thumbnail_name"];
$VideoTitle = $find_elements["file_name"];
$Video_name = $find_elements["video_title"];
$UploadDate = $find_elements["uploaded_on"];
$uploaded_username = $find_elements["uploader_username"];
$view_counter = $find_elements["view_count"];

$sample_rate = 10; //TO FINISH
if(mt_rand(1,$sample_rate) == 1) {
  $sql_update = "UPDATE videos SET view_count = view_count + 1 WHERE id = ".$_SESSION['videoToDisplay']."";
  if ($link->query($sql_update) === TRUE) {

  }
}


$num_per_page=15;
if (isset($_GET["page"])){
  $page=$_GET["page"];
}else{
  $page=1;
}
$start_from=($page-1)*$num_per_page;

$_SESSION['CurrentPage'] = $page;

//help
// echo "<a style='color: white' href='index.php'>ID: <strong>".$_SESSION['videoToDisplay']."</strong></a>";
// echo "<br>";
// echo "<a style='color: white' href='index.php'>Current page: <strong>".$_SESSION['CurrentPage']."</strong></a>";
// echo "<br>";
// echo "<a style='color: white' href='index.php'>Total Pages: <strong>".$_SESSION['Total Page']."</strong></a>";
// echo "<br>";

$sql = "SELECT * FROM videos ORDER BY RAND() LIMIT $start_from,$num_per_page";
$rs_result=mysqli_query($link,$sql);


?>

<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://unpkg.com/plyr@3.7.3/dist/plyr.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/plyr@3.7.3/dist/plyr.css" />
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\display_video.css">
    <link rel="stylesheet" href="css\pagination.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>

  <body class="background">
    <?php require 'php/loader.php'; ?>
    <?php require 'php/nav_bar.php'; ?>
    <div class="item1">
      <div class="order1">
        <div class="crop_img1">  
          <video 
            loop
            crossorigin
            playsinline
            data-poster="<?php echo $VideoToDisplayThumbnails; ?>"
            id="player"
            class="js-player";
          >
            <!--Video file-->
            <source
              src="<?php echo $VideoToDisplayURL; ?>"
              type="video/mp4"
              size="1080"
            />
            <source
              src="<?php echo $VideoToDisplayURL; ?>"
              type="video/mp4"
              size="720"
            />
            <source
              src="<?php echo $VideoToDisplayURL; ?>"
              type="video/mp4"
              size="480"
            />
            <source
              src="<?php echo $VideoToDisplayURL; ?>"
              type="video/mp4"
              size="240"
            />

          </video>
        </div>
      </div>
    </div>
    <div class="item2">
      <div class="order2">
      <p class="img_Title1"><strong><?php echo $Video_name; ?></strong></p>  
          <?php 
            if(in_array($uploaded_username, $verified_accounts)){
              if(in_array($uploaded_username, $owner_accounts)){
                echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description1">'.$uploaded_username.' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
              }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description1">'.$uploaded_username.' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
              } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description1">'.$uploaded_username.'</p>'; }
          ?>
            <p class="img_Description1"><?php echo number_format($view_counter);?> views · <?php echo (time_elapsed_string($UploadDate));?></p> 
      </div>
    </div>

<div class="item">
      <div class="order">
          <?php
              while($row = mysqli_fetch_array($rs_result)){
                $thumbnail_URL = 'database/thumbnails/'.$row["thumbnail_name"];
                ?>
            <div class="crop_img">  
                 
              <?php echo "<a title=".$row["uploader_username"]." href=\"display_video.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $thumbnail_URL; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_time"><?php echo $row["video_lenght"]; ?></p> 
              <p class="img_Title"><strong><?php echo $row["video_title"]; ?></strong></p>  
              <?php 
              if(in_array($row["uploader_username"], $verified_accounts)){
                if(in_array($row["uploader_username"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["uploader_username"].'</p>'; }
              ?> 
              <p class="img_Description"><?php echo number_format($row["view_count"]);?> views · <?php echo (time_elapsed_string($row['uploaded_on']));?></p>
              <?php 
                if(isset($_SESSION['username'])){
                  if($_SESSION['username'] === $row["uploader_username"]){
                    // echo '<a href="profile.php">your video</a>'; to do
                  }
                }
              ?> 
            </div>
            <?php } ?>
            <?php 
              $sql = "SELECT * FROM videos";
              $rs_result=mysqli_query($link,$sql);
              $total_records=mysqli_num_rows($rs_result);
              $total_pages=ceil($total_records/$num_per_page);
            ?>
          </div>
        </div>

    <div class="center">
      <div class="pagination">
      <?php if (ceil($total_pages / $num_per_page) > 0): ?>
        <?php if ($page > 1): ?>
        <a href="display_video.php?page=<?php echo $page-1 ?>">Prev</a>
        <?php else: ?>
          <a href="display_video.php?page=<?php echo $page=1 ?>">Prev</a>
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
        echo "<a class='pagination' href='display_video.php?page=".$page."'>".$page." / ".$total_pages."</a>"; 
        ?>

        <?php if ($page >= $total_pages): ?>
          <a href="display_video.php?page=<?php echo $page=$total_pages ?>">Next</a>
        <?php else: ?>
          <a href="display_video.php?page=<?php echo $page+1 ?>">Next</a>
        <?php endif; ?>     
      </div>
    </div>
    <!-- <?php endif; echo "<a style='color: white' class='pagingNumbers' >".$page.'/'.$total_pages."</a>"; 
    $_SESSION['Total Page'] = $total_pages;?> -->
  </body>
</html> 
</html>