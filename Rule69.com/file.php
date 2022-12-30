<?php
session_start();
require 'php/database/config.php';
require 'php/database/staff.php';
require 'php/convert_time.php';

$sql = "SELECT * FROM files ORDER BY creation_date DESC";
$rs_result=mysqli_query($link,$sql);
?>
                               
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">

    <link rel="stylesheet" href="css\file.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background">
    <?php require 'php/loader.php'; ?>
    <?php require 'php/nav_bar.php'; ?>

    <br><br><br>

    <?php 
    if(mysqli_num_rows($rs_result) > 0){?>
    <div class="center">
      <div class="order2">
          <?php
            while($row = mysqli_fetch_array($rs_result)){
              $thumbnail = 'database/files/thumbnails/'.$row["thumbnail"];
              $time_ago = (time_elapsed_string($row['creation_date']));
              ?>
            <div class="crop_img">   
              <?php echo "<a href=\"display_file.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $thumbnail; ?>" loading="lazy" style=object-fit:cover; width=100% height=100% ></a>
              <p class="img_Title"><strong><?php echo $row["file_title"]; ?></strong></p>  
              <?php 
                  if(in_array($row["file_creator_username"], $verified_accounts)){
                    if(in_array($row["file_creator_username"], $owner_accounts)){
                      echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["file_creator_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                    }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["file_creator_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                    } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["file_creator_username"].'</p>'; }
                ?>
              <p class="img_Description"><?php echo $time_ago;?></p>  
            </div>
          <?php } ?>
      </div>
    </div>
    <br>
    <?php }else{ echo "<p class='nothing'><strong>nothing yet :(</strong></p>"; } ?>
  </body>
</html>