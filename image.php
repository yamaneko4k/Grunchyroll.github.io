<?php
session_start();
require 'php/database/config.php';
require 'php/database/staff.php';
require 'php/convert_time.php';

$num_per_page=45;
if (isset($_GET["page"])){
  $page=$_GET["page"];
}else{
  $page=1;
}
$start_from=($page-1)*$num_per_page;

$sql = "SELECT * FROM images ORDER BY uploaded_on DESC limit $start_from,$num_per_page";
$rs_result=mysqli_query($link,$sql);
?>
                               
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="css\image.css">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\pagination.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>
  <body class="background">

    <?php require 'php/loader.php'; ?>
    <?php require 'php/nav_bar.php'; ?>

    <br>
    <?php if(mysqli_num_rows($rs_result) > 0){?>
    <div class="item">
      <div class="order">
          <?php
              while($row = mysqli_fetch_array($rs_result)){
                $imageURL = 'database/images/'.$row["file_name"];
                $time_ago = (time_elapsed_string($row['uploaded_on']));
                ?>
            <div class="crop_img">   
              <?php echo "<a title=".$row["uploader_username"]." href=\"display_image.php?id={$row['id']}\"</a>"; ?><img  id="img01" src="<?php echo $imageURL; ?>" loading="lazy"></a>
              <p class="img_Title"><strong><?php echo $row["image_title"]; ?></strong></p>  
              <?php 
              if(in_array($row["uploader_username"], $verified_accounts)){
                if(in_array($row["uploader_username"], $owner_accounts)){
                  echo '<p style="cursor: pointer;" title="'.$owner_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';
                }else{echo '<p style="cursor: pointer;" title="'.$verified_accounts_title.'" class="img_Description">'.$row["uploader_username"].' <a style="cursor: pointer;" title="'.$check_marck_title.'">&#x2713;</a></p>';}
                } else { echo '<p style="cursor: pointer;" title="'.$civilian_title.'" class="img_Description">'.$row["uploader_username"].'</p>'; }
              ?>
              <p class="img_Description"><?php echo number_format($row["view_count"]); ?> views · <?php echo $time_ago;?></p>
              <?php 
                if(isset($_SESSION['username'])){
                  if($_SESSION['username'] === $row["uploader_username"]){
                    // echo '<a href="profile.php">your image</a>'; to do
                  }
                }
              ?> 
              
            </div>
            <?php } ?>
            <?php 
              $sql = "SELECT * FROM images";
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
        <a href="image.php?page=<?php echo $page-1 ?>">Prev</a>
        <?php else: ?>
          <a href="image.php?page=<?php echo $page=1 ?>">Prev</a>
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
        echo "<a class='pagination' href='image.php?page=".$page."'>".$page." / ".$total_pages."</a>"; 
        $_SESSION['image_current_page'] = $page;
        ?>

        <?php if ($page >= $total_pages): ?>
          <a class='active' href="image.php?page=<?php echo $page=$total_pages ?>"><strong>Next</strong></a>
        <?php else: ?>
          <a href="image.php?page=<?php echo $page+1 ?>">Next</a>
        <?php endif; ?>     
      </div>
    </div>
    <?php endif; 
    if(mysqli_num_rows($rs_result) > 0){
      // echo "<a style='color: white' class='pagingNumbers'>".$page.'/'.$total_pages."</a>";
    }else{ echo "<p class='nothing'><strong>nothing yet :(</strong></p>";} 
    $_SESSION['Total Page'] = $total_pages; ?>
    <?php }else{ echo "<p class='nothing'><strong>nothing yet :(</strong></p>"; } ?>
  </body>
</html>