<?php
session_start();

// Destroy all session data
session_unset();  // Removes all session variables
session_destroy();  // Destroys the session

// Redirect to the homepage 
header("Location: Homepage.html");  
exit;
?>