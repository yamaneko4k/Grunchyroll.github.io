<?php
session_start();
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
    $page=1;
  }
if(isset($_POST['submit'])){
    require_once 'config.php';
    require 'traffic.php';
    require '../convert_size.php';
    require '../string_generator.php';
    $title = mysqli_real_escape_string($link,$_POST['blog_title']);
    $content = mysqli_real_escape_string($link,$_POST['editor']);

    $encoder = generateRandomString();
    $target_file = "../../database/blogs/thumbnails/" .$encoder. basename($_FILES["file"]["name"]);
    $fileName = $encoder. basename($_FILES["file"]["name"]);
    $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $update_blog_id = $_SESSION['blog_to_edit'];

    if(!empty($title) && !empty($content) && !empty($update_blog_id)){
        
        if (!empty($_FILES["file"]["name"]))
        {
            if(in_array($fileType, $allowTypes))
            {
                if($max_size_blog_thumbnail >= $_FILES["file"]["size"]){

                    $temp = explode(".", $_FILES["file"]["name"]);
                    $NewThumbnailName = round(microtime(true)) . '.' . end($temp);
    
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], "../../database/blogs/thumbnails/". $NewThumbnailName))
                    {
                        $sql = "SELECT thumbnail FROM blogs WHERE id = $update_blog_id"; 
                        $sql_result = mysqli_query($link,$sql);
                        $crawler = mysqli_fetch_array($sql_result);
                        $thumbnail = '../../database/blogs/thumbnails/'.$crawler["thumbnail"];
                        unlink($thumbnail);
    
                        $sql = "UPDATE blogs SET blog_title = '$title', blog_content = '$content', thumbnail = '$NewThumbnailName' WHERE id = '$update_blog_id';" ;
                        $execute = mysqli_query($link,$sql);
                    
                        if(!$execute){
                            echo "serveur error";
                            header('location:../../blog_edit.php?blog_to_edit='."$update_blog_id".'');
                            exit();
                        }else{
                            echo 'Sucessfully Uploaded !';
                            header('location:../../blog.php?successfully_uploaded');
                            exit();
                        }
                    }
                }else{
                    $fileSize = $_FILES["file"]["size"];
                    $limit = formatSizeUnits($max_size_blog_thumbnail);
                    $fileSize = formatSizeUnits($fileSize);
                    $_SESSION['error_thumbnail'] = "the thumbnail is ".$fileSize. " the limit is " .$limit;
                    header('location:../../blog_edit.php?blog_to_edit='."$update_blog_id".'');
                    if (!empty($title)){
                        $_SESSION['recover_title'] = trim($_POST['blog_title']);
                    }
                    if (!empty($content)){
                        $_SESSION['recover_content'] = trim($_POST['editor']);
                    }
                    exit();
                }
            }else{
                $_SESSION['error_thumbnail'] = "only jpg, jpeg, png ,gif, webp, jfif";
                header('location:../../blog_edit.php?blog_to_edit='."$update_blog_id".'');
                if (!empty($title)){
                    $_SESSION['recover_title'] = trim($_POST['blog_title']);
                }
                if (!empty($content)){
                    $_SESSION['recover_content'] = trim($_POST['editor']);
                }
                exit();
            }
        }
        else
        {
            $sql = "UPDATE blogs SET blog_title = '$title', blog_content = '$content' WHERE id = '$update_blog_id';" ;
            $execute = mysqli_query($link,$sql);
        
            if(!$execute){
                echo "serveur error";
                header('location:../../blog_edit.php?blog_to_edit='."$update_blog_id".'');
                exit();
            }else{
                echo 'Sucessfully Uploaded !';
                header('location:../../blog.php?successfully_uploaded');
                exit();
            }
        }
    }else{
        if (empty($title)){
            $_SESSION['error_title'] = "Please add a blog title.";
        }
        if (empty($content)){
            $_SESSION['error_content'] = "Please add a some text";
        }
        if (!empty($title)){
            $_SESSION['recover_title'] = trim($_POST['blog_title']);
        }
        if (!empty($content)){
            $_SESSION['recover_content'] = trim($_POST['editor']);
        }
        header('location:../../blog_edit.php?blog_to_edit='."$update_blog_id".'');
        exit();
        
    }
    
}else{
    header('location:../../profile.php?page='.$page.'');
    exit();
    
}
?>