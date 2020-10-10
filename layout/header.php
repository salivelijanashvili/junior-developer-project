<header>
  <nav class="navbar sticky-top topnav" id="header">
    <?php
    session_start();
    if (isset($_SESSION["loggedin"])  || isset($_COOKIE["PHTARM"])) {
      echo ' <a href="index.php">Home</a>
            <div class="navbar-right">
              <a href="create.php">Add product</a>
              <a class="active" href="profile.php">Profile</a>
              <a href="logout.php">Logout</a>
              <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
            </div>';
    } else {
      echo '<a href="index.php">Home</a>
           <div class="navbar-right">
            <a href="create.php">Add Product</a>
            <a href="login.php">LogIn</a>
            <a href="register.php">Signup</a>
            <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
           </div>';
    };
    ?>
  </nav>
</header>