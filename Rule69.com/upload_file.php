<?php
session_start();
require 'php/database/config.php';
require 'php/string_generator.php';
require 'php/convert_size.php';
require 'php/database/traffic.php';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}

// Define variables and initialize with empty values
$file_title = $file_name = $file_thumbnail = "";
$file_title_err = $file_name_err = $file_thumbnail_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  // Validate file_title
  if(empty(trim($_POST["file_title"]))){
      $file_title_err = "Please add a file title.";
  }elseif(strlen(trim($_POST["file_title"])) < 6){
    $file_title_err = "file title must have atleast 6 characters.";
    $file_title = trim($_POST["file_title"]);
  }else{
    $file_title = trim($_POST["file_title"]);
  }

  // Validate file_name
  if(empty(trim($_FILES["file"]["name"]))){
      $file_err = "Please add a file.";     
  }else{
      $file_name = $_FILES["file"]["name"];
  }

  if(!empty(trim($_FILES["file_thumbnail"]["name"]))){

    $fileSize = $_FILES["file_thumbnail"]["size"];
    if ($fileSize > $max_size_file_thumbnail)
    {
      $limit = formatSizeUnits($max_size_file_thumbnail);
      $fileSize = formatSizeUnits($fileSize);
      $file_thumbnail_err = "the thumbnail is ".$fileSize. " the limit is " .$limit;
    }
  }else{ $file_thumbnail_err = "Please add a thumbnail."; }

  //SETUP
  $username = $_SESSION['username'];
  $encoder = generateRandomString();
  $file_target_file = "database/files/content" .$encoder. basename($_FILES["file"]["name"]);
  $fileName = $encoder. basename($_FILES["file"]["name"]);
  $fileSize = $_FILES["file"]["size"];
  $limit = (1048576 * 10);
  $allowTypes = array('zip','rar','mp4');
  $fileType = strtolower(pathinfo($file_target_file,PATHINFO_EXTENSION));

  if ($fileSize > $max_size_file)
  {
    $limit = formatSizeUnits($max_size_file);
    $fileSize = formatSizeUnits($fileSize);
    $file_err = "the file is ".$fileSize. " the limit is " .$limit;
  }

  //Check file type
  if(in_array($fileType, $allowTypes)){
    // Check input errors before inserting in database
    if(empty($file_title_err) && empty($file_err) && empty($file_thumbnail_err)){

      if(!empty($file_title) && !empty($username) && !empty($_FILES["file_thumbnail"]["name"]) && !empty($_FILES["file"]["name"]))
      {       
        if(in_array($fileType, $allowTypes))
        {
            $thumbnail_target_file = "database/files/thumbnails/" .$encoder. basename($_FILES["file_thumbnail"]["name"]); 
            $thumbnailName = $encoder. basename($_FILES["file_thumbnail"]["name"]);
            $allowTypesThumbnail = array('jpg','jpeg','png','gif','webm','jfif');
            $thumbnailType = strtolower(pathinfo($thumbnail_target_file,PATHINFO_EXTENSION));  

            if(in_array($thumbnailType, $allowTypesThumbnail))
            {
              $temp = explode(".", $_FILES["file"]["name"]);
              $NewfileName = round(microtime(true)) . '.' . end($temp);

              if(move_uploaded_file($_FILES["file"]["tmp_name"], "database/files/content/" . $NewfileName))
              {
                $temp = explode(".", $_FILES["file_thumbnail"]["name"]);
                $NewThumbnailName = round(microtime(true)) . '.' . end($temp);

                if(move_uploaded_file($_FILES["file_thumbnail"]["tmp_name"], "database/files/thumbnails/" . $NewThumbnailName))
                {

                  $sql = "INSERT INTO files (file_name,file_creator_username,file_title,thumbnail) VALUE ('$NewfileName','$username','$file_title','$NewThumbnailName')";
                  $execute = mysqli_query($link,$sql);
              
                  if(!$execute){
                    header('location:upload_file.php?upload_fail');
                    $file_title_err = "serveur error";
                  }else{
                    header('location:file.php');
                    exit();
                  }
                }
              } 
            }else{
              $file_thumbnail_err = "only jpg, jpeg, png, gif, webm, jfif";
            } 
        }else{
            echo 'file type not accepted';
            header('location:upload_file.php?file_type_error');
            exit();
        }
      }else{
          header('location:upload_file.php?empty_data');
          exit();
      }
    }else{
      if(empty(trim($_FILES["file_thumbnail"]["name"]))){
        $file_thumbnail_rec = $rec;
      }
    }
  }else{
    if (empty($_FILES["file"]["name"]))
    {
      $file_err = "Please add a file";
    }
    if (!empty($_FILES["file"]["name"])){
      $file_err = "only zip, rar";
    }
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
    <link rel="stylesheet" href="css\upload_file.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br>

    <div class="container">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="upload-form">
      <div class="form-group">
        <br>
        <h1>FILE</h1>
        <br>
        <label>FILE TITLE (required)</label>
        <input type="text" name="file_title" class="form-control <?php echo (!empty($file_title_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $file_title; ?>">
        <span class="invalid-feedback"><?php echo $file_title_err; ?></span>
        <br>
        <label>FILE (required)</label>
        <input type="file" name="file" accept="file/*" class="form-control <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $file_name; ?>">
        <span class="invalid-feedback"><?php echo $file_err; ?></span>
        <br>
        <label>FILE THUMBNAIL (required)</label>
        <input type="file" name="file_thumbnail" accept="image/*" class="form-control <?php echo (!empty($file_thumbnail_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $file_thumbnail; ?>">
        <span class="invalid-feedback"><?php echo $file_thumbnail_err; ?></span>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text">Upload</span>
        </button>
      </form>
      <br><br>
    </div>
  </body>
</html>