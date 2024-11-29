<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the Sign In page
header("Location: signin.php");
exit();
?>