<?php require_once "layout/header.php"  ?>
<?php
require_once "server/config.php";
session_start();
if (isset($_COOKIE["PHTARM"])) {
} elseif (!isset($_SESSION["id"])) {
  header("Location:login.php");
}
// get currently logged user from mysql with session or cookie 
$res = "SELECT * FROM users WHERE id=" . $id;
$result = mysqli_query($link, $res);
$userRow = mysqli_fetch_array($result);
// set session and coockie
$id = "";
if (isset($_COOKIE["PHTARM"])) {
  $id = $_COOKIE["PHTARM"];
} elseif (isset($_SESSION["id"])) {
  $id = $_SESSION["id"];
}
// Define variables and initialize with empty values
$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate name
  $input_name = trim($_POST["name"]);
  if (empty($input_name)) {
    $name_err = "Please enter a name.";
  } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
    $name_err = "Please enter a valid name.";
  } else {
    $name = $input_name;
  }
  // Validate address
  $input_address = trim($_POST["address"]);
  if (empty($input_address)) {
    $address_err = "Please enter an announcement.";
  } else {
    $address = $input_address;
  }
  // Validate salary
  $input_salary = trim($_POST["salary"]);
  if (empty($input_salary)) {
    $salary_err = "Please enter the salary amount.";
  } elseif (!ctype_digit($input_salary)) {
    $salary_err = "Please enter a positive integer value.";
  } else {
    $salary = $input_salary;
  }
  // Check input errors before inserting in database
  if (empty($name_err) && empty($address_err) && empty($salary_err)) {
    // Prepare an insert statement
    $sql = "INSERT INTO employees (id ,name, address, salary) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "isss", $param_id, $param_name, $param_address, $param_salary);
      // Set parameters
      $param_id = $id;
      $param_name = $name;
      $param_address = $address;
      $param_salary = $salary;
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: index.php");
        exit();
      } else {
        echo "Something went wrong. Please try again later.";
      }
    }
    // Close statement
    mysqli_stmt_close($stmt);
  }
  // Close connection
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Create</title>
  <link rel="icon" type="image/png" href="https://freepngimg.com/download/newspaper/6-2-newspaper-png-clipart.png" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/index.js" defer></script>

</head>

<body>
  <main class="main-container">
    <div class="wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div>
              <h2>Product Add</h2>
            </div>
            <p>Please fill this form and submit to add product list to the database.</p>
            <form method="post">
              <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>SKU</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" autocomplete="off">
                <span class="help-block"><?php echo $name_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                <span class="help-block"><?php echo $address_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                <label>Prize</label>
                <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>" autocomplete="off">
                <span class="help-block"><?php echo $salary_err; ?></span>
              </div>
              <input type="submit" class="btn btn-primary" value="Submit">
              <a href="profile.php" class="btn btn-default">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>

</html>