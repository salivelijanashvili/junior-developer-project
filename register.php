<?php
require_once "server/config.php";
// Define variables and initialize with empty values
$username = $fullname = $email = $password = $confirm_password = "";
$username_err = $fullname_err = $email_err = $password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validate fullname
    if (empty($_POST["fullname"])) {
        $fullname_err = "Please enter a Fullname";
    } else {
        $sql = "SELECT id FROM users WHERE fullname = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_fullname);
            // Set parameters
            $param_fullname = trim($_POST["fullname"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $fullname = trim($_POST["fullname"]);
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Validate email
    if (empty($_POST["email"])) {
        $fullname_err = "Please enter a E-mail";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "This E-mail is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before inserting in database
    if (empty($username_err) && empty($fullname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username,fullname,email,password) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_fullname, $param_email, $param_password);
            // Set parameters
            $param_username = $username;
            $param_fullname = $fullname;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
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
    <title>Register</title>
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
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account.</p>
            </div>
            <form class="generalFom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" class="form-control" id="newUser" value="<?php echo $username; ?>" placeholder="name" autocomplete="off">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($fullname_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="fullname" class="form-control" value="<?php echo $fullname; ?>" placeholder="fullname" autocomplete="off">
                    <span class="help-block"><?php echo $fullname_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="E-mail">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="password">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="confirm password">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-default" value="Reset">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>
    </main>
</body>

</html>