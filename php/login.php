<?php
 //login.php
// Handles user login authentication

header('Content-Type: application/json');
session_start(); // Start the session to store user data
require_once 'db_config.php'; // Include database configuration

$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit();
}

try {
    // Prepare a statement to fetch user by username
    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the provided password against the stored hash
     if (password_verify($password, $user['password_hash'])) {
    // Password is correct, create session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // Store user role in session

    $redirect_page = '';
    if ($user['role'] === 'employee') {
        $redirect_page = 'staff_kpi_report.html'; // Redirect employees to KPI report
    } elseif ($user['role'] === 'manager' || $user['role'] === 'hr') {
        $redirect_page = 'index.html'; // Redirect managers/HR to dashboard or employee list
    } else {
        $redirect_page = 'index.html'; // Default redirect
    }

    echo json_encode(['success' => true, 'message' => 'Login successful!', 'role' => $user['role'], 'redirect' => $redirect_page]);
} 

else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

?>

