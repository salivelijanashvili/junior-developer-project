<?php
session_start();
require_once "server/config.php";

$id = "";
if (isset($_COOKIE["PHTARM"])) {
  $id = $_COOKIE["PHTARM"];
} elseif (isset($_SESSION["id"])) {
  $id = $_SESSION["id"];
}
// get currently logged user from mysql with session or cookie 
$res = "SELECT * FROM users WHERE id=" . $id;
$result = mysqli_query($link, $res);
$userRow = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="icon" type="image/png" href="https://freepngimg.com/download/newspaper/6-2-newspaper-png-clipart.png" />
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  <script src="js/index.js" defer></script>
</head>

<body>
  <?php require_once 'layout/header.php'; ?>
  <main class="main-container">
    <div class="profile-container">

      <div class="page-header">
        <h4>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h4>
        <h5>Name: <?php echo $userRow["username"]  ?></h5>
        <h5>Fullname: <?php echo $userRow["fullname"]  ?></h5>
        <h5>E-mail: <?php echo $userRow["email"]  ?></h5>
        <a href="reset-password.php" class="btn btn-warning">Edit profile</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        <!-- <form action="upload.php" method="post" enctype="multipart/form-data">
          Select image to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload Image" name="submit">
        </form> -->
      </div>

      <div class="wrapper wraper-profile res">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="clearfix">
                <h2 class="pull-left">Your Products</h2>
                <a href="create.php" class="btn btn-success pull-right">Add New </a>
              </div>
              <?php
              // Include config file
              require_once "server/config.php";
              session_start();
              // Attempt select query execution
              $sql = "SELECT * FROM employees";
              if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                  echo "<table class='table table-bordered table-striped'>";
                  echo "<thead>";
                  echo "<tr>";
                  // echo "<th>#</th>";
                  echo "<th>SKU</th>";
                  echo "<th>Product name</th>";
                  echo "<th>Price</th>";
                  echo "<th>Action</th>";
                  echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while ($row = mysqli_fetch_array($result)) {
                    if ($_SESSION["id"] == $row["id"] || $_COOKIE["PHTARM"] == $row["id"]) {
                      echo "<tr>";
                      // echo "<td>" . $row['id'] . "</td>";
                      echo "<td>" . $row['name'] . "</td>";
                      echo "<td>" . $row['address'] . "</td>";
                      echo "<td>" . "$" . $row['salary'] . "</td>";
                      echo "<td>";
                      echo "<a href='read.php?id=" . $row['user_id'] . "' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                      echo "<a href='update.php?id=" . $row['user_id'] . "' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                      echo "<a href='delete.php?id=" . $row['user_id'] . "' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                      echo "</td>";

                      echo "</tr>";
                    }
                  }
                  echo "</tbody>";
                  echo "</table>";
                  // Free result set
                  mysqli_free_result($result);
                } else {
                  echo "<p class='lead'><em>No records were found.</em></p>";
                }
              } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
              }

              // Close connection
              mysqli_close($link);
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>


</html>