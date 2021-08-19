<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo'dashboard.php'?>"><i class="fa fa-home nav-home"></i> <?php echo lang('HOME'); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li><a class="nav-link" aria-current="page" href="<?php echo'categories.php'?>"><?php echo lang('CATEGORIES'); ?></a></li>
        <li><a class="nav-link" aria-current="page" href="<?php echo"items.php"?>"><?php echo lang('ITEMS'); ?></a></li>
        <li><a class="nav-link" aria-current="page" href="<?php echo'members.php'?>"><?php echo lang('MEMBERS'); ?></a></li>
        <li><a class="nav-link" aria-current="page" href="<?php echo'comments.php'?>"><?php echo lang('COMMENTS'); ?></a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup ='false' aria-expanded="false">
            <?php echo lang('SELECTION'); ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="../index.php">Visit Shop</a></li>
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']?>"><?php echo lang('EDIT'); ?></a></li>
            <li><a class="dropdown-item" href="logout.php"><?php echo lang('EXIT'); ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>