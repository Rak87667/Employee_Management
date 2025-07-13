    <?php
    // delete_employee.php
    // Deletes an employee from the database

    header('Content-Type: application/json');
    require_once 'db_config.php';

    $input = json_decode(file_get_contents('php://input'), true);

    $id = $input['id'] ?? null;

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Employee ID is required.']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Employee deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete employee.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting employee: ' . $e->getMessage()]);
    }
    ?>
    