<?php
session_start();
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
    $page=1;
  }
if(isset($_POST['submit'])){
    require_once 'config.php';
    require '../string_generator.php';
    require '../convert_size.php';
    require 'traffic.php';
    $video_title = mysqli_real_escape_string($link,$_POST['video_title']);

    $encoder = generateRandomString();
    $target_file_thumbnail = "../../database/thumbnails/" .$encoder. basename($_FILES["video_thumbnail"]["name"]);
    $fileName = $encoder. basename($_FILES["video_thumbnail"]["name"]);
    $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
    $fileType = strtolower(pathinfo($target_file_thumbnail,PATHINFO_EXTENSION));
    $limit = (1048576 * 10);

    $video_to_edit_id = $_SESSION['video_to_edit'];

    if(!empty($video_title) && strlen($video_title) >= 6 && !empty($video_to_edit_id))
    {
        
        if (!empty($_FILES["video_thumbnail"]["name"]))
        {
            if(in_array($fileType, $allowTypes))
            {
                if($max_size_video_thumbnail >= $_FILES["video_thumbnail"]["size"])
                {
                    $temp = explode(".", $_FILES["video_thumbnail"]["name"]);
                    $NewThumbnailName = round(microtime(true)) . '.' . end($temp);
    
                    if(move_uploaded_file($_FILES["video_thumbnail"]["tmp_name"], "../../database/thumbnails/" . $NewThumbnailName))
                    {
                        $sql = "SELECT thumbnail_name FROM videos WHERE id = $video_to_edit_id"; 
                        $sql_result = mysqli_query($link,$sql);
                        $crawler = mysqli_fetch_array($sql_result);
                        $image = '../../database/thumbnails/'.$crawler["thumbnail_name"];
                        unlink($image);
    
                        $sql = "UPDATE videos SET video_title = '$video_title', thumbnail_name = '$NewThumbnailName' WHERE id = '$video_to_edit_id';" ;
                        $execute = mysqli_query($link,$sql);
                    
                        if(!$execute){
                            echo "serveur error";
                            header('location:../../edit_video.php?video_to_edit='."$video_to_edit_id");
                            exit();
                        }else{
                            echo 'Sucessfully Uploaded !';
                            header('location:../../profile.php?page='.$page.'');
                            exit();
                        }
                    }
                }else{
                    $fileSize = $_FILES["video_thumbnail"]["size"];
                    $limit = formatSizeUnits($max_size_video_thumbnail);
                    $fileSize = formatSizeUnits($fileSize);

                    $_SESSION['error_thumbnail'] = "the video is ".$fileSize. " the limit is " .$limit;
                    $_SESSION['recover_title_video'] = trim($_POST['video_title']);
                    header('location:../../edit_video.php?video_to_edit='."$video_to_edit_id");
                    exit(); 
                }
            }else{
                $_SESSION['error_thumbnail'] = "only jpg, jpeg, png, gif, webp, jfif";
                $_SESSION['recover_title_video'] = trim($_POST['video_title']);
                header('location:../../edit_video.php?video_to_edit='."$video_to_edit_id");
                exit(); 
            }
        }
        else
        {
            $sql = "UPDATE videos SET video_title = '$video_title' WHERE id = '$video_to_edit_id'";
            $execute = mysqli_query($link,$sql);
        
            if(!$execute){
                echo "serveur error";
                header('location:../../edit_video.php?video_to_edit='."$video_to_edit_id");
                exit();
            }else{
                echo 'Sucessfully Uploaded !';
                header('location:../../profile.php?page='.$page.'');
                exit();
            }
        }
    }else{
        if (empty($video_title)){
            $_SESSION['error_title'] = "Please add a video title.";
        }
        if (!empty($video_title)){
            $_SESSION['error_title'] = "video title must have atleast 6 characters.";
            $_SESSION['recover_title_video'] = trim($_POST['video_title']);
        } 
        if (!empty($_FILES["video_thumbnail"]["name"])){
            
            if($max_size_video_thumbnail >= $_FILES["video_thumbnail"]["size"]){
                $fileSize = $_FILES["video_thumbnail"]["size"];
                $limit = formatSizeUnits($max_size_video_thumbnail);
                $fileSize = formatSizeUnits($fileSize);

                $_SESSION['error_thumbnail'] = "the thumbnail is ".$fileSize. " the limit is " .$limit;
            }
            if(!in_array($fileType, $allowTypes)){
                $_SESSION['error_thumbnail'] = "only jpg, jpeg, png, gif, webp, jfif";
            }
        }
        header('location:../../edit_video.php?video_to_edit='."$video_to_edit_id");
        exit();
    }
    
}else{
    echo "invalid request";
    header('location:../../profile.php?page='.$page.'');
    exit();
    
}
?>