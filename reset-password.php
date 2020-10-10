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
$username = $fullname  = $new_password = $confirm_password = "";
$username_err = $fullname_err = $new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter the new username";
    }
    // validate fullname
    if (empty(trim($_POST["fullname"]))) {
        $fullname_err = "Please enter the new fullname";
    }
    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have atleast 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET username = ?, fullname = ?, password = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_username, $param_fullname, $param_password, $param_id);
            // Set parameters
            $param_username = $_POST["username"];
            $param_fullname = $_POST["fullname"];
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $id;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully
                header("location: profile.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="icon" type="image/png" href="https://freepngimg.com/download/newspaper/6-2-newspaper-png-clipart.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/index.js" defer></script>
</head>

<body>
    <?php require_once "layout/header.php" ?>
    <main class="main-container">
        <div class="wrapper">
            <div class="form-title">
                <h2>Edit profile</h2>
            </div>
            <form class="generalFom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" class="form-control" id="newUser" value="<?php echo $userRow["username"]; ?>" placeholder="name" autocomplete="off">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($fullname_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="fullname" class="form-control" value="<?php echo $userRow["fullname"]; ?>" placeholder="fullname" autocomplete="off">
                    <span class="help-block"><?php echo $fullname_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" placeholder="new password">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="confirm password">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="edit">
                    <a class="btn btn-link" href="profile.php">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>