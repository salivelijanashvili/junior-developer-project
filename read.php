<?php
require_once "server/config.php";
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Prepare a select statement
    $sql = "SELECT * FROM employees WHERE user_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        // Set parameters
        $param_id = trim($_GET["id"]);
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                // Retrieve individual field value
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: server/error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: server/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="icon" type="image/png" href="https://freepngimg.com/download/newspaper/6-2-newspaper-png-clipart.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/index.js" defer></script>

</head>

<body>
    <?php require_once "layout/header.php"  ?>
    <main class="main-container">
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h1>View Product</h1>
                        </div>
                        
                        <div class="form-group">
                            <label>SKU</label>
                            <p class="form-control-static"><?php echo $row["name"]; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <p class="form-control-static"><?php echo $row["address"]; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <p class="form-control-static"><?php echo "$" . $row["salary"]; ?></p>
                        </div>
                        <p><a href="index.php" class="btn btn-primary">Back</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>