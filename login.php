<?php
session_start();
require_once "server/config.php";
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit();
}
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            // Set parameters
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            // $hashCookie = password_hash($_SESSION["id"], PASSWORD_DEFAULT);
                            if (isset($_POST["check"])) {
                                $expires = time() + 3600;
                                setcookie("PHTARM", $_SESSION["id"], $expires, "/");
                            } else {
                                $expires = time() + 1;
                                setcookie("PHTARM", $_SESSION["id"], $expires, "/");
                            }
                            // Redirect user to profile page
                            header("location: profile.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
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
    <title>Login</title>
    <link rel="icon" type="image/png" href="https://freepngimg.com/download/newspaper/6-2-newspaper-png-clipart.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/index.js" defer></script>
</head>

<body>
    <?php require_once 'layout/header.php'; ?>
    <main class="main-container">
        <div class="wrapper wrapper-form">
            <div class="form-title">
                <h2>Login</h2>
                <p>Please fill in your credentials to login.</p>
            </div>
            <form class="generalFom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" class="form-control" id="name" value="<?php echo $username; ?>" placeholder="E-mail/username" autocomplete="off">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-control" placeholder="password">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group check">
                    <input type="checkbox" name="check" id="check">
                    <label for="check">Remember me</label>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            </form>
        </div>
    </main>
</body>

</html>