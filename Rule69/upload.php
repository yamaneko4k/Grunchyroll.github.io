<?php
session_start();
require 'php/database/config.php';

?>
                               
<html>
  <head>
    <?php require 'php/database/information.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\loader.css">
    <link rel="stylesheet" href="css\upload.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script defer src="script\script.js"></script>
  </head>

  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>

    <br><br><br>

    <div class="center">
      <div class="order">
        <h3>Welcome to the Upload Section</h3>
        <hr>
        <p>Rule69Hub provides a wide range of video, images, posts, and files. Be  part of it :)</p>
      </div>
    </div>

    <div class="center">
      <div class="order">
        <h2>Community Posts</h2>
        <p>Rule69Hub provides a wide range of video and images. This is the part were you can post about your interests. This section will contain different kind of post such as tutorials, blog, updates, patchs and more</p>
        <a href="blog_create.php"  class="a_btn" >Create new Posts</a>
      </div>
    </div>

    <div class="center">
      <div class="order">
        <h2>Create a Video</h2>
        <p>Rule69Hub provides a wide range of video and images. This is the part were you can post about your interests. This section will contain different kind of post such as tutorials, blog, updates, patchs and more</p>
        <a href="upload_video.php"  class="a_btn" >Upload Video</a>
      </div>
    </div>

    <div class="center">
      <div class="order">
        <h2>Create an Image</h2>
        <p>Rule69Hub provides a wide range of video and images. This is the part were you can post about your interests. This section will contain different kind of post such as tutorials, blog, updates, patchs and more</p>
        <a href="upload_image.php"  class="a_btn" >Upload Image</a>
      </div>
    </div>
    
    <div class="center">
      <div class="order">
        <h2>Upload Files</h2>
        <p>Rule69Hub provides a wide range of video and images. This is the part were you can post about your interests. This section will contain different kind of post such as tutorials, blog, updates, patchs and more</p>
        <a href="upload_file.php"  class="a_btn" >Upload Files</a>
      </div>
    </div>

    <div class="center">
      <div class="order">
        <h2>Create Tags</h2>
        <p>Rule69Hub provides a wide range of video and images. This is the part were you can post about your interests. This section will contain different kind of post such as tutorials, blog, updates, patchs and more</p>
        <a href="add_tags.php"  class="a_btn" >Add Tags</a>
      </div>
    </div>


    <br>
    
  </body>
</html>