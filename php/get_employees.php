<?php
// php/get_employees.php
// Fetches all employee records from the database.
// Accessible by 'manager', 'hr', and 'IT' roles.

header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if the user is authenticated and has the correct role to view employees
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'hr' && $_SESSION['role'] !== 'IT')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

try {
    // Select the 'salary' column instead of 'kpi_summary'
    $stmt = $pdo->query("SELECT id, name, position, department, contact, salary FROM employees ORDER BY name ASC");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'employees' => $employees]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>