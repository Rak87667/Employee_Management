<?php
// php/add_employee.php
// Handles adding a new employee record.
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

$name = $input['name'] ?? '';
$position = $input['position'] ?? '';
$department = $input['department'] ?? '';
$contact = $input['contact'] ?? '';
$salary = $input['salary'] ?? null; // Changed from kpi_summary

if (empty($name) || empty($position) || empty($department) || empty($contact)) {
    echo json_encode(['success' => false, 'message' => 'Name, position, department, and contact are required.']);
    exit();
}

try {
    // Insert into 'salary' column
    $stmt = $pdo->prepare("INSERT INTO employees (name, position, department, contact, salary) VALUES (:name, :position, :department, :contact, :salary)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':salary', $salary); // Changed from kpi_summary

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'បុគ្គលិកត្រូវបានបន្ថែមដោយជោគជ័យ!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'បរាជ័យក្នុងការបន្ថែមបុគ្គលិក។']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
