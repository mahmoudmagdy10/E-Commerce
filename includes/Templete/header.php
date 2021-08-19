<!-- header.inc => For include only not open in the browser
headre.php => can open in the browser  -->
<?php ob_start();
error_reporting(0); //For ignoring all warnings use this sample, on the top of your code;
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8">
    	<title><?php echo getTitle();?></title>
     	<link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css;?>all.css">
        <link rel="stylesheet" href="<?php echo $css;?>jquery-ui.css">
        <link rel="stylesheet" href="<?php echo $css;?>jquery.selectBoxIt1.css">
        <link rel="stylesheet" href="<?php echo $css;?>front.css">
    </head>
    <body>
    <div class="upper-bar">
      <div class="container">
        <?php
          if (isset($_SESSION['user'])) {?>
              <img class='img-responsive text-center img-thumbnails img-circle center-block float-right' src='img.png'>
              <span><a href='profile.php'><?php echo $sessionUser; ?></a></span>
              <div class="links">
                <span><a href='profile.php'>My Profile</a></span>
                <span><a href='profile.php#my-items'>My Items</a></span>
                <span><a href='newitem.php'>New Item</a></span>
                <span><a href='logout.php'>Logout</a></span>
              </div>
<?php
           } else {
           ?>
          <a href="login.php">
            <span class="float-right upper">Log In</span>
          </a>
        <?php }?>
        </div> 
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo'index.php'?>"><i class="fa fa-home"></i> HomePage</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse float-right navbar-collapse" id="app-nav">
          <ul class="navbar-nav navbar-right">
            <?php 
                $allCats = getAllFrom("*","categories","where Parent = 0","","ID","ASC");
                foreach($allCats as $cat) {

                    echo'<li>
                          <a class="nav-link" aria-current="page" href="categories.php?pageid='.$cat['ID'].'">'.$cat['Name'].'</a>
                        </li>';
                }
            ?>
          </ul>
        </div>
      </div>
    </nav>
    <?php ob_end_flush();?>