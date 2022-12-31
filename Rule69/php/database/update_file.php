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
    $file_title = mysqli_real_escape_string($link,$_POST['file_title']);

    $encoder = generateRandomString();
    $target_file_thumbnail = "../../database/files/thumbnails/" .$encoder. basename($_FILES["file_thumbnail"]["name"]);
    $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
    $fileType = strtolower(pathinfo($target_file_thumbnail,PATHINFO_EXTENSION));

    $file_to_edit_id = $_SESSION['file_to_edit'];

    if(!empty($file_title) && strlen($file_title) >= 6 && !empty($file_to_edit_id))
    {
        if (!empty($_FILES["file_thumbnail"]["name"]))
        {
            if(in_array($fileType, $allowTypes))
            {
                if($max_size_file_thumbnail >= $_FILES["file_thumbnail"]["size"])
                {
                    $temp = explode(".", $_FILES["file_thumbnail"]["name"]);
                    $NewThumbnailName = round(microtime(true)) . '.' . end($temp);
    
                    if(move_uploaded_file($_FILES["file_thumbnail"]["tmp_name"], "../../database/files/thumbnails/" . $NewThumbnailName))
                    {
                        $sql = "SELECT thumbnail FROM files WHERE id = $file_to_edit_id"; 
                        $sql_result = mysqli_query($link,$sql);
                        $crawler = mysqli_fetch_array($sql_result);
                        $image = '../../database/files/thumbnails/'.$crawler["thumbnail"];
                        unlink($image);
    
                        $sql = "UPDATE files SET file_title = '$file_title', thumbnail = '$NewThumbnailName' WHERE id = '$file_to_edit_id';" ;
                        $execute = mysqli_query($link,$sql);
                    
                        if(!$execute){
                            echo "serveur error";
                            header('location:../../edit_file.php?file_to_edit='."$file_to_edit_id");
                            exit();
                        }else{
                            echo 'Sucessfully Uploaded !';
                            header('location:../../profile.php?page='.$page.'');
                            exit();
                        }
                    }
                }else{
                    $fileSize = $_FILES["file_thumbnail"]["size"];
                    $limit = formatSizeUnits($max_size_file_thumbnail);
                    $fileSize = formatSizeUnits($fileSize);

                    $_SESSION['error_thumbnail'] = "the file is ".$fileSize. " the limit is " .$limit;
                    $_SESSION['recover_title_file'] = trim($_POST['file_title']);
                    header('location:../../edit_file.php?file_to_edit='."$file_to_edit_id");
                    exit(); 
                }
            }else{
                $_SESSION['error_thumbnail'] = "only jpg, jpeg, png, gif, webp, jfif";
                $_SESSION['recover_title_file'] = trim($_POST['file_title']);
                header('location:../../edit_file.php?file_to_edit='."$file_to_edit_id");
                exit(); 
            }
        }
        else
        {
            $sql = "UPDATE files SET file_title = '$file_title' WHERE id = '$file_to_edit_id'";
            $execute = mysqli_query($link,$sql);
        
            if(!$execute){
                echo "serveur error";
                header('location:../../edit_file.php?file_to_edit='."$file_to_edit_id");
                exit();
            }else{
                echo 'Sucessfully Uploaded !';
                header('location:../../profile.php?page='.$page.'');
                exit();
            }
        }
    }else{
        if (empty($file_title)){
            $_SESSION['error_title'] = "Please add a file title.";
        }
        if (!empty($file_title)){
            $_SESSION['error_title'] = "file title must have atleast 6 characters.";
            $_SESSION['recover_title_file'] = trim($_POST['file_title']);
        } 
        if (!empty($_FILES["file_thumbnail"]["name"])){
            
            if($max_size_file_thumbnail >= $_FILES["file_thumbnail"]["size"]){
                $fileSize = $_FILES["file_thumbnail"]["size"];
                $limit = formatSizeUnits($max_size_file_thumbnail);
                $fileSize = formatSizeUnits($fileSize);

                $_SESSION['error_thumbnail'] = "the thumbnail is ".$fileSize. " the limit is " .$limit;
            }
            if(!in_array($fileType, $allowTypes)){
                $_SESSION['error_thumbnail'] = "only jpg, jpeg, png, gif, webp, jfif";
            }
        }
        header('location:../../edit_file.php?file_to_edit='."$file_to_edit_id");
        exit();
    }
    
}else{
    echo "invalid request";
    header('location:../../profile.php?page='.$page.'');
    exit();
    
}
?>