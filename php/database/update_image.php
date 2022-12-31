<?php
session_start();
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
    $page=1;
  }
if(isset($_POST['submit'])){
    require_once 'config.php';
    require '../string_generator.php';
    $image_title = mysqli_real_escape_string($link,$_POST['image_title']);

    $image_to_edit_id = $_SESSION['image_to_edit'];

    if(!empty($image_title) && strlen($image_title) >= 6 && !empty($image_to_edit_id))
    {        
        $sql = "UPDATE images SET image_title = '$image_title' WHERE id = '$image_to_edit_id';" ;
        $execute = mysqli_query($link,$sql);
    
        if(!$execute){
            echo "serveur error";
            header('location:../../edit_image.php?image_to_edit='."$image_to_edit_id".'serveur_error');
            exit();
        }else{
            echo 'Sucessfully Uploaded !';
            header('location:../../profile.php?page='.$page.'');
            exit();
        }

    }else{
        if (empty($image_title)){
            $_SESSION['error_title'] = "Please add a image title.";
            header('location:../../edit_image.php?image_to_edit='."$image_to_edit_id");
            exit(); 
        }else{
            $_SESSION['error_title'] = "Image title must have atleast 6 characters.";
            $_SESSION['recover_title_image'] = trim($_POST['image_title']);
            header('location:../../edit_image.php?image_to_edit='."$image_to_edit_id");
            exit(); 
        }   
    }
    
}else{
    echo "invalid request";
    header('location:../../profile.php?page='.$page.'');
    exit();
    
}
?>