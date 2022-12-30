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
$image_title = $image_file =  $image_tags = "";
$image_title_err = $image_file_err = $image_tags_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  // Validate image_title
  if(empty(trim($_POST["image_title"]))){
      $image_title_err = "Please add a image title.";
  }elseif(strlen(trim($_POST["image_title"])) < 6){
    $image_title_err = "image title must have atleast 6 characters.";
    $image_title = trim($_POST["image_title"]);
  }else{
    $image_title = trim($_POST["image_title"]);
  }

  // Validate image_file
  if(empty(trim($_FILES["image_file"]["name"]))){
      $image_file_err = "Please add a image file.";     
  }else{
      $image_file = $_FILES["image_file"]["name"];
  }

  //SETUP
  $username = $_SESSION['username'];
  $encoder = generateRandomString();
  $image_target_file = "database/images/" .$encoder. basename($_FILES["image_file"]["name"]);
  $fileName = $encoder. basename($_FILES["image_file"]["name"]);
  $fileSize = $_FILES["image_file"]["size"];
  $limit = (1048576 * 10);
  $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
  $fileType = strtolower(pathinfo($image_target_file,PATHINFO_EXTENSION));

  if ($fileSize > $max__size_image)
  {
    $limit = formatSizeUnits($max__size_image);
    $fileSize = formatSizeUnits($fileSize);
    $image_file_err = "the image is ".$fileSize. " the limit is " .$limit;
  }

  //Check file type
  if(in_array($fileType, $allowTypes)){
    // Check input errors before inserting in database
    if(empty($image_title_err) && empty($image_file_err)){

      if(!empty($image_title) && !empty($username) && !empty($_FILES["image_file"]["name"]))
      {       
        if(in_array($fileType, $allowTypes))
        {   
    
            $image_target_file = "database/images/" .$encoder. basename($_FILES["image_file"]["name"]); 
            $imageName = $encoder. basename($_FILES["image_file"]["name"]);
            $allowTypesimage = array('jpg','jpeg','png','gif','webp','jfif');
            $imageType = strtolower(pathinfo($image_target_file,PATHINFO_EXTENSION));  

            if(in_array($imageType, $allowTypesimage))
            {
              $temp = explode(".", $_FILES["image_file"]["name"]);
              $NewImageName = round(microtime(true)) . '.' . end($temp);
              
              if(move_uploaded_file($_FILES["image_file"]["tmp_name"], "database/images/" . $NewImageName))
              {
                $sql = "INSERT INTO images (file_name,uploader_username,view_count,image_title) VALUE ('$NewImageName','$username','". 0 ."','$image_title')";
                $execute = mysqli_query($link,$sql);

                if(!$execute){
                    header('location:upload_image.php?upload_fail');
                    exit();
                }else{
                  header('location:image.php');
                  exit();
                }
              } 
            }else{
              $image_thumbnail_err = "only jpg, jpeg, png ,gif, webp, jfif";
            }

            
          
        }else{
            echo 'file type not accepted';
            header('location:upload_image.php?file_type_error');
            exit();
        }
      }else{
          header('location:upload_image.php?empty_data');
          exit();
      }
    }
  }else{
    if (empty($_FILES["image_file"]["name"]))
    {
      $image_file_err = "Please add a image";
    }else{
      $image_file_err = "only jpg, jpeg, png, gif, webp, jfif";
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
    <link rel="stylesheet" href="css\upload_image.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
  </head>
  
  <?php 
    $sql = "SELECT * FROM tags";
    $tags = mysqli_query($link,$sql);
  ?>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br>

    <div class="container">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <br>
        <h1>IMAGE</h1>
        <br>
        <label>IMAGE TITLE (required)</label>
        <input type="text" name="image_title" class="form-control <?php echo (!empty($image_title_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $image_title; ?>">
        <span class="invalid-feedback"><?php echo $image_title_err; ?></span>
        <br>
        <label>IMAGE FILE (required)</label>
        <input type="file" name="image_file" accept="image/*" class="form-control <?php echo (!empty($image_file_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $image_file; ?>">
        <span class="invalid-feedback"><?php echo $image_file_err; ?></span>
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
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text">Upload</span>
        </button>
      </form>
      <br><br>
    </div>
  </body>
</html>