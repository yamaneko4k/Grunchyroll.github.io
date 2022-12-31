<?php
session_start();
require 'php/database/config.php';
require 'php/string_generator.php';
require 'php/convert_size.php';
require 'php/database/traffic.php';
require("getID3/getid3/getid3.php");
$getID3 = new getID3;

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}

// Define variables and initialize with empty values
$video_title = $video_file = $video_thumbnail = $video_tags = "";
$video_title_err = $video_file_err = $video_thumbnail_err = $video_tags_err = "";
$video_thumbnail_rec = '';
$rec = 'no thumbnail ?';
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  // Validate video_title
  if(empty(trim($_POST["video_title"]))){
      $video_title_err = "Please add a video title.";
  }elseif(strlen(trim($_POST["video_title"])) < 6){
    $video_title_err = "video title must have atleast 6 characters.";
    $video_title = trim($_POST["video_title"]);
  }else{
    $video_title = trim($_POST["video_title"]);
  }

  // Validate video_file
  if(empty(trim($_FILES["video_file"]["name"]))){
      $video_file_err = "Please add a video file.";     
  }else{
      $video_file = $_FILES["video_file"]["name"];
  }

  if(!empty(trim($_FILES["video_thumbnail"]["name"]))){

    $fileSize = $_FILES["video_thumbnail"]["size"];
    $limit = (1048576 * 10);
    if ($fileSize > $max_size_video_thumbnail)
    {
      $limit = formatSizeUnits($max_size_video_thumbnail);
      $fileSize = formatSizeUnits($fileSize);
      $video_thumbnail_err = "the thumbnail is ".$fileSize. " the limit is " .$limit;
    }
  }

  //SETUP
  $username = $_SESSION['username'];
  $encoder = generateRandomString();
  $video_target_file = "database/videos/" .$encoder. basename($_FILES["video_file"]["name"]);
  $fileName = $encoder. basename($_FILES["video_file"]["name"]);
  $fileSize = $_FILES["video_file"]["size"];
  $limit = (1048576 * 10);
  $allowTypes = array('mp4','webm','mkv');
  $fileType = strtolower(pathinfo($video_target_file,PATHINFO_EXTENSION));

  if ($fileSize > $max_size_video)
  {
    $limit = formatSizeUnits($max_size_video);
    $fileSize = formatSizeUnits($fileSize);
    $video_file_err = "the video is ".$fileSize. " the limit is " .$limit;
  }

  //Check file type
  if(in_array($fileType, $allowTypes)){
    // Check input errors before inserting in database
    if(empty($video_title_err) && empty($video_file_err) && empty($video_thumbnail_err)){

      if(!empty($video_title) && !empty($username) && !empty($_FILES["video_file"]["name"]))
      {       
        if(in_array($fileType, $allowTypes))
        {
          if(empty(trim($_FILES["video_thumbnail"]["name"])))
          {
            $temp = explode(".", $_FILES["video_file"]["name"]);
            $NewVideoName = round(microtime(true)) . '.' . end($temp);

            if(move_uploaded_file($_FILES["video_file"]["tmp_name"],"database/videos/" . $NewVideoName))
            {
              require 'ffmpeg/vendor/autoload.php';
    
              $thumbnail_name = floor(microtime(true) * 1000).'.jpg';
              $thumbnail_location = 'database/thumbnails/'.$thumbnail_name;

              $ffmpeg = \FFMpeg\FFMpeg::create([
                  'ffmpeg.binaries'  => 'ffmpeg/vendor/bin/ffmpeg.exe',
                  'ffprobe.binaries' => 'ffmpeg/vendor/bin/ffprobe.exe' 
              ]);

              $new_video_target_file = "database/videos/" . $NewVideoName;

              $video = $ffmpeg->open($new_video_target_file);
              $video
                  ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0.5))
                  ->save($thumbnail_location); 



              $video_raw ="database/videos/" . $NewVideoName;
              $video_file = $getID3->analyze($video_raw);
              $duration_string = $video_file['playtime_string'];
         
              $sql = "INSERT INTO videos (file_name,uploader_username,view_count,video_title,thumbnail_name,video_lenght) VALUE ('$NewVideoName','$username','". 0 ."','$video_title','$thumbnail_name','$duration_string')";
              $execute = mysqli_query($link,$sql);

              $video_id = mysqli_insert_id($link);
              $tags = $_POST['select'];

              $row = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM tags WHERE tag_name = '$tags'"));
              $tag_id = $row['id'];
              $final_execute = mysqli_query($link,"INSERT INTO search_videos (videoID,tagID,video_name,tag_name,username) VALUE ('$video_id','$tag_id','$NewVideoName','$tags','$username')");


          
              if(!$execute || !$final_execute){
                  header('location:upload_video.php?upload_fail');
                  $video_title_err = "serveur error";
              }else{
                header('location:video.php?file=$tags_array');
                exit();
              }      
            }    
          }
          else
          {     
            $thumbnail_target_file = "database/thumbnails/" .$encoder. basename($_FILES["video_thumbnail"]["name"]); 
            $thumbnailName = $encoder. basename($_FILES["video_thumbnail"]["name"]);
            $allowTypesThumbnail = array('jpg','jpeg','png','gif','webm','jfif');
            $thumbnailType = strtolower(pathinfo($thumbnail_target_file,PATHINFO_EXTENSION));  

            if(in_array($thumbnailType, $allowTypesThumbnail))
            {
              $temp = explode(".", $_FILES["video_file"]["name"]);
              $NewVideoName = round(microtime(true)) . '.' . end($temp);

              if(move_uploaded_file($_FILES["video_file"]["tmp_name"], "database/videos/" . $NewVideoName))
              {
                $temp = explode(".", $_FILES["video_thumbnail"]["name"]);
                $NewThumbnailName = round(microtime(true)) . '.' . end($temp);

                if(move_uploaded_file($_FILES["video_thumbnail"]["tmp_name"], "database/thumbnails/" . $NewThumbnailName))
                {
                  $video_raw ="database/videos/" . $NewVideoName;
                  $video_file = $getID3->analyze($video_raw);
                  $duration_string = $video_file['playtime_string'];

                  $sql = "INSERT INTO videos (file_name,uploader_username,view_count,video_title,thumbnail_name,video_lenght) VALUE ('$NewVideoName','$username','". 0 ."','$video_title','$NewThumbnailName','$duration_string')";
                  $execute = mysqli_query($link,$sql);
              
                  if(!$execute){
                    header('location:upload_video.php?upload_fail');
                    $video_title_err = "serveur error";
                  }else{
                    header('location:video.php');
                    exit();
                  }
                }
              } 
            }else{
              $video_thumbnail_err = "only jpg, jpeg, png, gif, webm, jfif";
            }

            
          }
        }else{
            echo 'file type not accepted';
            header('location:upload_video.php?file_type_error');
            exit();
        }
      }else{
          header('location:upload_video.php?empty_data');
          exit();
      }
    }else{
      if(empty(trim($_FILES["video_thumbnail"]["name"]))){
        $video_thumbnail_rec = $rec;
      }
    }
  }else{
    if (empty($_FILES["video_file"]["name"]))
    {
      $video_file_err = "Please add a video";
    }
    if (!empty($_FILES["video_file"]["name"])){
      $video_file_err = "only mp4, webm, mkv";
    }
    if(empty(trim($_FILES["video_thumbnail"]["name"]))){
      $video_thumbnail_rec = $rec;
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
    <link rel="stylesheet" href="css\upload_video.css">
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
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="upload-form">
      <div class="form-group">
        <br>
        <h1>VIDEO</h1>
        <br>
        <label>VIDEO TITLE (required)</label>
        <input type="text" name="video_title" class="form-control <?php echo (!empty($video_title_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $video_title; ?>">
        <span class="invalid-feedback"><?php echo $video_title_err; ?></span>
        <br>
        <label>VIDEO FILE (required)</label>
        <input type="file" name="video_file" accept="video/*" class="form-control <?php echo (!empty($video_file_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $video_file; ?>">
        <span class="invalid-feedback"><?php echo $video_file_err; ?></span>
        <br>
        <label>CONTENT (recommended)</label>
        <select class="form-control" name="select">
        <!-- <select class="form-control" name="select" multiple multiselect-search="true" multiselect-max-items="2"> -->
          <?php
            while($row = mysqli_fetch_array($tags)){
              $tag = $row["tag_name"];
          ?>
        <option><?php echo $tag; ?></option>
          <?php } ?>
        </select>
        <br>
        <label>THUMBNAIL (recommended)</label>
        <input type="file" name="video_thumbnail" accept="image/*" class="form-control <?php echo (!empty($video_thumbnail_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $video_thumbnail; ?>">
        <span class="invalid-feedback"><?php echo $video_thumbnail_err; ?></span>
        <b><?php echo $video_thumbnail_rec; ?></b>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text">Upload</span>
        </button>
      </form>
      <br><br>
    </div>
  </body>
</html>