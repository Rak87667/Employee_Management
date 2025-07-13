<?php
// check_auth.php
// Checks if a user is authenticated and returns their role

header('Content-Type: application/json');
session_start(); // Start the session to access session variables

if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    // User is logged in
    echo json_encode([
        'authenticated' => true,
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role']
    ]);
} else {
    // User is not logged in
    echo json_encode(['authenticated' => false]);
}
?>