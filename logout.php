<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: redirect to login page
header("Location: login.php");
exit;
?>
