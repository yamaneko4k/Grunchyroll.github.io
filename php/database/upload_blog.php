<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}
if(isset($_POST['submit'])){
    require_once 'config.php';
    require 'traffic.php';
    require '../convert_size.php';
    require '../string_generator.php';
    $title = mysqli_real_escape_string($link,$_POST['blog_title']);
    $content = mysqli_real_escape_string($link,$_POST['editor']);
    $username = $_SESSION['username'];
    $encoder = generateRandomString();
    $target_file = "../../database/blogs/thumbnails/" .$encoder. basename($_FILES["file"]["name"]);
    $fileName = $encoder. basename($_FILES["file"]["name"]);
    $allowTypes = array('jpg','jpeg','png','gif','webp','jfif');
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    if(!empty($title) && !empty($content) && !empty($username) && !empty($_FILES["file"]["name"])){
        
        if(in_array($fileType, $allowTypes))
        {
            if($max_size_blog_thumbnail >= $_FILES["file"]["size"])
            {
                $temp = explode(".", $_FILES["file"]["name"]);
                $NewVideoName = round(microtime(true)) . '.' . end($temp);
                
                if(move_uploaded_file($_FILES["file"]["tmp_name"], "../../database/blogs/thumbnails/" . $NewVideoName))
                {
                    $sql = "INSERT INTO blogs (blog_title,blog_content,blog_creator,thumbnail) VALUE ('$title','$content','$username','".$NewVideoName."')";
                    $execute = mysqli_query($link,$sql);
                
                    if(!$execute){
                        if (!empty($title)){
                            $_SESSION['recover_title'] = trim($_POST['blog_title']);
                        }
                        if (!empty($content)){
                            $_SESSION['recover_content'] = trim($_POST['editor']);
                        }
                        header('location:../../blog_create.php?upload_fail');
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
                if (!empty($title)){
                    $_SESSION['recover_title'] = trim($_POST['blog_title']);
                }
                if (!empty($content)){
                    $_SESSION['recover_content'] = trim($_POST['editor']);
                }
                header('location:../../blog_create.php?file_too_large');
                exit();
            }
        }else{
            $_SESSION['error_thumbnail'] = "only jpg, jpeg, png ,gif, webp, jfif";
            header('location:../../blog_create.php?file_type_error');
            if (!empty($title)){
                $_SESSION['recover_title'] = trim($_POST['blog_title']);
            }
            if (!empty($content)){
                $_SESSION['recover_content'] = trim($_POST['editor']);
            }
            exit();
        }
    }else{
        if (empty($title)){
            $_SESSION['error_title'] = "Please add a blog title.";
        }
        if (empty($content)){
            $_SESSION['error_content'] = "Please add a some text";
        }
        if(empty($_FILES["file"]["name"])){
            $_SESSION['error_thumbnail'] = "Please add a thumbnail";
        }
        if (!empty($title)){
            $_SESSION['recover_title'] = trim($_POST['blog_title']);
        }
        if (!empty($content)){
            $_SESSION['recover_content'] = trim($_POST['editor']);
        }
        header('location:../../blog_create.php?empty_title');
        exit();
    }
    
}else{
    header('location:../../profile.php?invalid_request');
    exit();
}
?>