<?php 
session_start();
require 'config.php';
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
  $page=1;
}

if(isset($_SESSION['image_to_delete'])){
    $pass_image = $_SESSION['image_to_delete'];
    $sql = "SELECT file_name FROM images WHERE id = $pass_image"; 

    $sql_result = mysqli_query($link,$sql);
    $crawler = mysqli_fetch_array($sql_result);

    $image = glob('../../database/images/'.$crawler["file_name"]);

    foreach($image as $file) {
        
      if(is_file($file)){
        if (unlink($file)){
          $delete_sql = "DELETE FROM images WHERE id = $pass_image"; 
          mysqli_query($link,$delete_sql); 
          echo 'success';
        }
      }
    }
    unset($_SESSION['image_to_delete']);
    header('location:../../profile.php?page='.$page.'');
    exit();
  }

  if(isset($_SESSION['video_to_delete'])){
    $pass_video = $_SESSION['video_to_delete'];
    $sql = "SELECT * FROM videos WHERE id = $pass_video"; 

    $sql_result = mysqli_query($link,$sql);
    $crawler = mysqli_fetch_array($sql_result);

    try{$video = glob('../../database/videos/'.$crawler["file_name"]);
    $thumbnail = glob('../../database/thumbnails/'.$crawler["thumbnail_name"]);}catch(\Throwable $e){throw new \Exception('is null');}
  
    foreach($video as $file) {
        
      if(is_file($file)){
        if (unlink($file)){
          $delete_sql = "DELETE FROM videos WHERE id = $pass_video"; 
          mysqli_query($link,$delete_sql);
          echo 'success';
        }
      }
    }
    foreach($thumbnail as $file) {
        
      if(is_file($file)){
        if (unlink($file)){
          $delete_sql = "DELETE FROM videos WHERE id = $pass_video"; 
          mysqli_query($link,$delete_sql);
          echo 'success';
        }
      }
    }
    unset($_SESSION['video_to_delete']);
    header('location:../../profile.php?page='.$page.'');
    exit();
  }

  if(isset($_SESSION['blog_to_delete'])){
    $pass_blog = $_SESSION['blog_to_delete'];
    $sql = "SELECT thumbnail FROM blogs WHERE id = $pass_blog"; 

    $sql_result = mysqli_query($link,$sql);
    $crawler = mysqli_fetch_array($sql_result);

    try{$thumbnail = glob('../../database/blogs/thumbnails/'.$crawler["thumbnail"]);}catch(\Throwable $e){throw new \Exception('is null');}

    foreach($thumbnail as $file) {
        
      if(is_file($file)){
        if (unlink($file)){
          $delete_sql = "DELETE FROM blogs WHERE id = $pass_blog"; 
          mysqli_query($link,$delete_sql);
          echo 'success';
        }
      }
    }
    unset($_SESSION['blog_to_delete']);
    header('location:../../profile.php?page='.$page.'');
    exit();
  }

  if(isset($_SESSION['file_to_delete'])){
    $pass_file = $_SESSION['file_to_delete'];
    $sql = "SELECT * FROM files WHERE id = $pass_file"; 

    $sql_result = mysqli_query($link,$sql);
    $crawler = mysqli_fetch_array($sql_result);

    try{$files = glob('../../database/files/content/'.$crawler["file_name"]);
      $thumbnail = glob('../../database/files/thumbnails/'.$crawler["thumbnail"]);}catch(\Throwable $e){throw new \Exception('is null');}
    
      foreach($files as $file) {
          
        if(is_file($file)){
          if (unlink($file)){
            $delete_sql = "DELETE FROM files WHERE id = $pass_file"; 
            mysqli_query($link,$delete_sql);
            echo 'success';
          }
        }
      }
      foreach($thumbnail as $file) {
          
        if(is_file($file)){
          if (unlink($file)){
            $delete_sql = "DELETE FROM files WHERE id = $pass_file"; 
            mysqli_query($link,$delete_sql);
            echo 'success';
          }
        }
      }
      unset($_SESSION['file_to_delete']);
      header('location:../../profile.php?page='.$page.'');
      exit();
  }

?>