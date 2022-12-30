<?php

// Database configuration
require 'config.php';

if($_SERVER['REQUEST_METHOD'] != "POST")
{     
  $folder_path1 = "../../database/images";
  $folder_path2 = "../../database/users";
  $folder_path3 = "../../database/videos";
  $folder_path4 = "../../database/thumbnails";
  $folder_path5 = "../../database/blogs/thumbnails";
  $folder_path6 = "../../database/blogs/contents";
  
  $files1 = glob($folder_path1.'/*'); 
  $files2 = glob($folder_path2.'/*'); 
  $files3 = glob($folder_path3.'/*'); 
  $files4 = glob($folder_path4.'/*'); 
  $files5 = glob($folder_path5.'/*'); 
  $files6 = glob($folder_path6.'/*'); 
      
  // foreach($files1 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }

  // foreach($files2 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }

  // foreach($files3 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }

  // foreach($files4 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }

  // foreach($files5 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }

  // foreach($files6 as $file) {
      
  //   if(is_file($file)){
  //     unlink($file); 
  //   }
  // }
  
  $delete = 'DELETE FROM images';
  $delete2 = 'DELETE FROM videos';
  $delete3 = 'DELETE FROM users';
  $delete4 = 'DELETE FROM blogs';

  if (mysqli_query($link, $delete)) 
  {
    if (mysqli_query($link, $delete2)) 
    {
      // if (mysqli_query($link, $delete3)) 
      // {
          // if (mysqli_query($link, $delete4)) 
          // {
             header('location:../../index.php');
          // }
      // } 
    }
  }
  else 
  {
    echo 'error';
  }
}
?>