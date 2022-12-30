<?php
session_start();
require 'php/database/config.php';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
}else{
  header("Location:login.php?you_need_to_login");
}
$page = $_SESSION['profile_current_page'];
if(!isset($_SESSION['profile_current_page'])){
  $page=1;
}
$current_username = $_SESSION['username'];
$blog_to_edit = $_GET['blog_to_edit'];
if (empty($blog_to_edit)){header('location:profile.php?page='.$page.'');}
$_SESSION['blog_to_edit'] = $blog_to_edit;
$sql = "SELECT * FROM blogs WHERE id = '".$blog_to_edit."' AND blog_creator = '".$current_username."'";
$rs_result=mysqli_query($link,$sql);
while($row = mysqli_fetch_array($rs_result)){
  $id = $row['id'];
  $blog_title = $row["blog_title"];
  $blog_content = $row["blog_content"];
  $thumbnail = 'database/blogs/thumbnails/'.$row["thumbnail"];
}

$blog_title_err = $blog_content_err = $blog_thumbnail_err = '';

if (isset($_SESSION['error_title']))
{
  $blog_title_err = $_SESSION['error_title'];
  $_SESSION['error_title'] = '';
}
if (isset($_SESSION['error_content']))
{
  $blog_content_err = $_SESSION['error_content'];
  $_SESSION['error_content'] = '';
}
if (isset($_SESSION['error_thumbnail']))
{
  $blog_thumbnail_err = $_SESSION['error_thumbnail'];
  $_SESSION['error_thumbnail'] = '';
}

$blog_title_recover = $blog_content_recover = '';

if (isset($_SESSION['recover_title'])){
  $blog_title_recover = $_SESSION['recover_title'];
  $_SESSION['recover_title'] = '';
  if ($blog_title_recover != ''){
    $blog_title = $blog_title_recover;
  }
}
if (isset($_SESSION['recover_content'])){
  $blog_content_recover = $_SESSION['recover_content'];
  $_SESSION['recover_content'] = '';
  if ($blog_content_recover != ''){
    $blog_content = $blog_content_recover;
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
    <link rel="stylesheet" href="css\blog_edit.css">
    <link rel="stylesheet" href="css\nav_bar.css">
    <script src="script\script.js"></script>
    <script src="ckeditor\ckeditor.js"></script>
    <link href="ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet">
    <script src="ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
  </head>
  
  <body class="background">
    <!-- <?php require 'php/loader.php'; ?> -->
    <?php require 'php/nav_bar.php'; ?>
    <br><br><br><br>

    <div class="container">
      <form action="php/database/update_blog.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>TUTORIAL TITLE</label>
        <input type="text" name="blog_title" class="form-control 'is-invalid'" value="<?php echo $blog_title ?>">
        <span class="span_err"><?php echo $blog_title_err ?></span>
        <br><br>
        <textarea name="editor" id="editor" rows=11 cols=50 maxlength=10000><?php echo $blog_content ?></textarea>
        <span class="span_err"><?php echo $blog_content_err ?></span>
        <br><br>
        <div class="contain_img">
          <img loading="lazy" src="<?php echo $thumbnail ?>">
        </div>
        <br>
        <br>
        <label>THUMBNAIL (optional) <b class="nice"> *you already have one*</b></label>
        <input type="file" name="file" class="form-control 'is-invalid'" accept="image/*" value='<?php echo $thumbnail ?>'/>
        <span class="span_err"><?php echo $blog_thumbnail_err ?></span>
        <br><br>
        <button onclick="this.classList.toggle('button--loading')" type="submit" name="submit" class="button"> 
            <span class="button__text"><strong>Upload</strong></span>
        </button>
      </form>
      <br><br>
    </div>
    <script>
    hljs.initHighlightingOnLoad();
    CKEDITOR.replace('editor', {
        filebrowserBrowseUrl: '/browser/browse.php',
        filebrowserUploadUrl: 'ckeditor/ck_upload.php',
        filebrowserUploadMethod: 'form'
    });
    </script>
  </body>
</html>