<?php
session_start();
session_unset(); // Clear session variables
session_destroy(); // Destroy the session

// Redirect to the thank you page
header("Location: thank_you.php");
exit();
