<?php
// retrieve usernames from database,  for fetch from index.js
require_once "config.php";
$usernames = [];
$data = "SELECT * FROM users;";
$result = mysqli_query($link, $data);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($usernames, $row["username"]);
        array_push($usernames, $row["email"]);
    }
}
echo json_encode($usernames);
