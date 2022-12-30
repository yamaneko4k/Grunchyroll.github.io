<?php
session_start();
require 'php/database/config.php';
require 'php/database/staff.php';
require 'php/convert_time.php';
date_default_timezone_set('Europe/London');

if(isset($_GET['id'])){
  $_SESSION['FileToDisplay']=$_GET['id'];
}

$FileID = $_SESSION['FileToDisplay'];

$sql_search = "SELECT * FROM files WHERE id=$FileID";
$sql_search_result = mysqli_query($link,$sql_search);
$find_elements = mysqli_fetch_array($sql_search_result);
$timestamp = strtotime($find_elements["creation_date"]);
?>


<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\display_blog.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background">
  <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br><br>
    <div class="center">
      <div class="order">
        <h3><strong><?php echo $find_elements["file_title"] ?></strong></h3>
        <hr>
        <?php 
          if(in_array($find_elements["file_creator_username"], $verified_accounts)){
            if(in_array($find_elements["file_creator_username"], $owner_accounts)){
              echo '<p>Posted <strong>'. time_elapsed_string($find_elements["creation_date"]) .'</strong> by <a style="cursor: pointer;" title="'.$owner_accounts_title.'"><strong>'. $find_elements["file_creator_username"] .'<a style="cursor: pointer;" title="'.$check_marck_title.'"> &#x2713</a></strong></p>';
            }else{echo '<p>Posted <strong>'. time_elapsed_string($find_elements["creation_date"]) .'</strong> by <a style="cursor: pointer;" title="'.$verified_accounts_title.'"><strong>'. $find_elements["file_creator_username"] .'<a style="cursor: pointer;" title="'.$check_marck_title.'"> &#x2713</a></p>';}
            } else { echo '<p>Posted <strong>'. time_elapsed_string($find_elements["creation_date"]) .'</strong> by <a style="cursor: pointer;" title="'.$civilian_title.'"><strong>'. $find_elements["file_creator_username"] .'</a></p>'; }
        ?>
      </div>
    </div>

    <div class="center">
      <div class="order">
      <?php echo "<a href=\"php/database/download.php?id={$find_elements['id']}\"</a>"; ?>Download</a>
      </div>
    </div>

    <br>
  </body>
</html>