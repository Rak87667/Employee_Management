<?php
// logout.php
// Destroys the user session and logs them out

session_start(); // Start the session
session_unset();   // Unset all session variables
session_destroy(); // Destroy the session

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
exit();
?>
