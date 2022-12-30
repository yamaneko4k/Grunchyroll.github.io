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

$banner_err = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $target_file_banner = "database/users/banners/". basename($_FILES["banner"]["name"]);
    $fileName = basename($_FILES["banner"]["name"]);
    $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
    $fileType = strtolower(pathinfo($target_file_banner,PATHINFO_EXTENSION));

    if (!empty($_FILES["banner"]["name"]))
    {
        if(in_array($fileType, $allowTypes))
        {
            if($max_size_banner >= $_FILES["banner"]["size"])
            {
                $temp = explode(".", $_FILES["banner"]["name"]);
                $NewbannerName = round(microtime(true)) . '.' . end($temp);

                if(move_uploaded_file($_FILES["banner"]["tmp_name"], "database/users/banners/" . $NewbannerName))
                {
                    $sql = "SELECT banner_name FROM users WHERE username = '$current_username'";
                    $sql_result = mysqli_query($link,$sql);
                    $crawler = mysqli_fetch_array($sql_result); //
                    $old_banner = 'database/users/banners/'.$crawler["banner_name"];
                    if ($old_banner != 'database/users/banners/default-banner.jpg'){
                        unlink($old_banner);
                    }
                    
                    $sql = "UPDATE users SET banner_name = '$NewbannerName' WHERE username = '$current_username'" ;
                    $execute = mysqli_query($link,$sql);
                
                    if(!$execute){
                        $banner_err = "serveur error";
                    }else{
                        echo 'Sucessfully Uploaded !';
                        header('location:profile.php?page='.$page.'');
                    }
                }
            }else{
                $fileSize = $_FILES["banner"]["size"];
                $limit = formatSizeUnits($max_size_banner);
                $fileSize = formatSizeUnits($fileSize);
                $banner_err = "the video is ".$fileSize. " the limit is " .$limit;
            }
        }else{
            $banner_err = "only jpg, jpeg, png, gif, webp, jfif";
        }
    }
    else
    {
        $banner_err = 'Enter a banner';
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
        <h1>EDIT BANNER</h1>
        <br>
        <label>ENTER BANNER</label>
        <input type="file" name="banner" class="form-control"> 
        <span class="span_err"><?php echo $banner_err ?></span>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text"><strong>Upload</strong></span>
        </button>
      </form>
    </div>
  </body>
</html>