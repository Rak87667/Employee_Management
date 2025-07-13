<?php
// php/update_employee.php
// Handles updating an existing employee record.
// Accessible only by 'manager' and 'hr' roles.

header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if the user is authenticated and has the correct role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'hr')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$name = $input['name'] ?? '';
$position = $input['position'] ?? '';
$department = $input['department'] ?? '';
$contact = $input['contact'] ?? '';
$salary = $input['salary'] ?? null; // Now correctly refers to 'salary'

if (empty($id) || empty($name) || empty($position) || empty($department) || empty($contact)) {
    echo json_encode(['success' => false, 'message' => 'Employee ID, name, position, department, and contact are required.']);
    exit();
}

try {
    // Update 'salary' column
    // The SQL query now correctly uses 'salary' instead of 'kpi_summary'
    $stmt = $pdo->prepare("UPDATE employees SET name = :name, position = :position, department = :department, contact = :contact, salary = :salary WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':salary', $salary); // Correctly binds to 'salary'
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'ព័ត៌មានបុគ្គលិកត្រូវបានកែប្រែដោយជោគជ័យ!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'បរាជ័យក្នុងការកែប្រែព័ត៌មានបុគ្គលិក។']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
