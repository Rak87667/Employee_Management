<?php
// php/create_user.php
// Allows 'IT' role users to create new user accounts for employees.

header('Content-Type: application/json');
session_start(); // Start the session to access user_id and role
require_once 'db_config.php'; // Include database configuration

// Check if the user is authenticated and has the 'IT' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'IT') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Only IT role can create users.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$role = $input['role'] ?? 'employee'; // Default role for new users is 'employee'

// Basic validation
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit();
}

// Validate role to prevent arbitrary role creation
$allowedRoles = ['employee', 'manager', 'hr', 'terminal operation']; // Add any other roles you want IT to be able to assign
if (!in_array($role, $allowedRoles)) {
    echo json_encode(['success' => false, 'message' => 'Invalid role specified.']);
    exit();
}

try {
    // Check if username already exists
    $stmt_check = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt_check->bindParam(':username', $username);
    $stmt_check->execute();
    if ($stmt_check->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(['success' => false, 'message' => 'Username already exists. Please choose a different username.']);
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $stmt_insert = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)");
    $stmt_insert->bindParam(':username', $username);
    $stmt_insert->bindParam(':password_hash', $hashed_password);
    $stmt_insert->bindParam(':role', $role);

    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'គណនីអ្នកប្រើប្រាស់ត្រូវបានបង្កើតដោយជោគជ័យ!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'បរាជ័យក្នុងការបង្កើតគណនីអ្នកប្រើប្រាស់។']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
