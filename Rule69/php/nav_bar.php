<html>
    <head> 
        <link rel="stylesheet" href="..\css\nav_bar.css">
    </head>
    <body>
        <div class="nav">
            <input type="checkbox" id="nav-check">
            <div class="nav-btn">
                <label for="nav-check">
                <span></span>
                <span></span>
                <span></span>
                </label>
            </div>
            
            <div class="nav-links">
                <a href="index.php"><strong>HOME</strong></a>
                <?php 
                if(isset($_SESSION['image_current_page'])){
                    echo '<a href="image.php?page='.$_SESSION['image_current_page'].'"><strong>IMAGES</strong></a>';
                }else{
                    echo '<a href="image.php"><strong>IMAGES</strong></a>';
                }
                if(isset($_SESSION['video_current_page'])){
                    echo '<a href="video.php?page='.$_SESSION['video_current_page'].'"><strong>VIDEOS</strong></a>';
                }else{
                    echo '<a href="video.php"><strong>VIDEOS</strong></a>';
                }
                ?>
                <a href="blog.php"><strong>FORUM</strong></a>
                <a href="file.php"><strong>FILES</strong></a>
                <a href="upload.php"><strong>UPLOAD</strong></a>
                <?php
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    if(!isset($_SESSION['profile_current_page'])){
                        $_SESSION['profile_current_page'] = 1;
                    }else{
                        echo '<a href="profile.php?page='.$_SESSION['profile_current_page'].'"><strong>PROFILE</strong></a>';
                    }
                    echo '<a href="php/database/logout.php" class="warning"><strong>LOG OUT</strong></a>';
                    if($_SESSION['username'] === 'yamaneko'){
                        // echo '<li class="li_nav"><a href="php/database/reset.php"><strong>RESET</strong></a></li>';
                    }
                }else{
                    echo '<a href="login.php"><strong>LOGIN</strong></a>';
                    echo '<a href="register.php"><strong>SIGN IN</strong></a>';
                }
                ?>


            </div>
        </div>

        <div class="nav_placement">
            <ul class="ul_nav">
                <li class="li_nav"><a href="index.php"><strong>HOME</strong></a></li>
                <?php 
                if(isset($_SESSION['image_current_page'])){
                    echo '<li class="li_nav"><a href="image.php?page='.$_SESSION['image_current_page'].'""><strong>IMAGES</strong></a></li>';
                }else{
                    echo '<li class="li_nav"><a href="image.php"><strong>IMAGES</strong></a></li>';
                }
                if(isset($_SESSION['video_current_page'])){
                    echo '<li class="li_nav"><a href="video.php?page='.$_SESSION['video_current_page'].'""><strong>VIDEOS</strong></a></li>';
                }else{
                    echo '<li class="li_nav"><a href="video.php"><strong>VIDEOS</strong></a></li>';
                }
                ?>
                <li class="li_nav"><a href="blog.php"><strong>FORUM</strong></a></li>
                <li class="li_nav"><a href="file.php"><strong>FILES</strong></a></li>
                <li class="li_nav"><a href="upload.php"><strong>UPLOAD</strong></a></li>
                <?php
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    echo '<li class="li_nav" style="float:right"><a href="php/database/logout.php"><strong>LOG OUT</strong></strong></a></li>';
                    if(!isset($_SESSION['profile_current_page'])){
                        $_SESSION['profile_current_page'] = 1;
                    }else{
                        echo '<li class="li_nav" style="float:right"><a href="profile.php?page='.$_SESSION['profile_current_page'].'"><strong>PROFILE</strong></a></li>';
                    }
                    if($_SESSION['username'] === 'yamaneko'){
                        // echo '<li class="li_nav"><a href="php/database/reset.php"><strong>RESET</strong></a></li>';
                    }
                }else{
                    echo '<li class="li_nav" style="float:right"><a href="login.php"><strong>LOGIN</strong></a></li>';
                    echo '<li class="li_nav" style="float:right"><a href="register.php"><strong>SIGN IN</strong></a></li>';
                }
                ?>
                <!-- <li class="li_nav" style="float:right"><form action="/action_page.php"><input type="text" class="search_bar" placeholder="Search.." name="search"></form></a></li> -->
            </ul>
        </div>
    </body>
</html>
