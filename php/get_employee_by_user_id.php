<?php
// php/get_employee_by_user_id.php
// Fetches employee details based on the logged-in user's ID.
// This is used by staff_kpi_report.html to display employee info.

header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch employee details using the user_id.
    // Ensure 'salary' is selected, not 'kpi_summary'.
    $stmt = $pdo->prepare("SELECT id, name, position, department, contact, salary FROM employees WHERE user_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {
        echo json_encode(['success' => true, 'employee' => $employee]);
    } else {
        echo json_encode(['success' => false, 'message' => 'មិនអាចផ្ទុកព័ត៌មានបុគ្គលិកបានទេ។ សូមប្រាកដថាអ្នកត្រូវបានភ្ជាប់ទៅគណនីបុគ្គលិក។']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
