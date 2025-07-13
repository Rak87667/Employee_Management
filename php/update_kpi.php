<?php
// php/update_kpi.php
// Handles updating the KPI summary for a specific employee.
// Crucially, it verifies that the update request is for the logged-in user's associated employee.

header('Content-Type: application/json');
session_start(); // Start the session to access user_id
require_once 'db_config.php'; // Include database configuration

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$employee_id = $input['employee_id'] ?? null;
$kpi_summary = $input['kpi_summary'] ?? '';

if (empty($employee_id)) {
    echo json_encode(['success' => false, 'message' => 'Employee ID is required.']);
    exit();
}

try {
    // First, verify that the employee_id provided in the request
    // is actually linked to the currently logged-in user's user_id.
    $stmt_check = $pdo->prepare("SELECT id FROM employees WHERE id = :employee_id AND user_id = :logged_in_user_id");
    $stmt_check->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt_check->bindParam(':logged_in_user_id', $logged_in_user_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $employee_exists_and_belongs_to_user = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$employee_exists_and_belongs_to_user) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Employee ID does not match logged-in user.']);
        exit();
    }

    // If authorized, proceed to update the KPI summary
    $stmt_update = $pdo->prepare("UPDATE employees SET kpi_summary = :kpi_summary WHERE id = :employee_id");
    $stmt_update->bindParam(':kpi_summary', $kpi_summary);
    $stmt_update->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        echo json_encode(['success' => true, 'message' => 'KPI report updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update KPI report.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
