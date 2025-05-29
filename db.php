<?php
// db.php
$servername = "localhost";
$username = "your_db_user";
$password = "your_db_password";
$dbname = "yg";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
