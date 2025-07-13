<?php
// php/add_daily_kpi_report.php
// Handles adding and updating daily KPI reports for employees.

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
$report_date = $input['report_date'] ?? null; // Date of the report
$tasks_completed = $input['tasks_completed'] ?? []; // Array of completed tasks
$problem_description = $input['problem_description'] ?? '';

// Validate required fields
if (empty($employee_id) || empty($report_date) || !is_array($tasks_completed)) {
    echo json_encode(['success' => false, 'message' => 'Missing required data (employee ID, report date, or tasks).']);
    exit();
}

// Ensure the employee_id belongs to the logged-in user for security
try {
    $stmt_check_employee = $pdo->prepare("SELECT id FROM employees WHERE id = :employee_id AND user_id = :logged_in_user_id");
    $stmt_check_employee->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt_check_employee->bindParam(':logged_in_user_id', $logged_in_user_id, PDO::PARAM_INT);
    $stmt_check_employee->execute();
    $employee_verified = $stmt_check_employee->fetch(PDO::FETCH_ASSOC);

    if (!$employee_verified) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Employee ID does not match logged-in user.']);
        exit();
    }

    // Calculate KPI Score based on the provided rules
    $kpi_score = 0;
    foreach ($tasks_completed as $task_name => $is_completed) {
        if ($is_completed) {
            // Extract the machine type from the task_name (e.g., "ATM_Servicing_CardReader" -> "ATM")
            $machine_type_prefix = explode('_', $task_name)[0];

            switch ($machine_type_prefix) {
                case 'ATM':
                    $kpi_score += 3;
                    break;
                case 'CRM':
                    $kpi_score += 3;
                    break;
                case 'CIM':
                case 'CDM':
                case 'CEM':
                case 'CCM':
                    $kpi_score += 2;
                    break;
                // Add more cases here if you have other machine types with different points
            }
        }
    }

    // Convert tasks_completed array to JSON string for storage
    $tasks_completed_json = json_encode($tasks_completed);

    // Check if a report for this employee and date already exists
    $stmt_check_report = $pdo->prepare("SELECT id FROM daily_kpi_reports WHERE employee_id = :employee_id AND report_date = :report_date");
    $stmt_check_report->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt_check_report->bindParam(':report_date', $report_date);
    $stmt_check_report->execute();
    $existing_report = $stmt_check_report->fetch(PDO::FETCH_ASSOC);

    if ($existing_report) {
        // Update existing report
        $stmt_update = $pdo->prepare("UPDATE daily_kpi_reports SET tasks_completed = :tasks_completed, kpi_score = :kpi_score, problem_description = :problem_description WHERE id = :report_id");
        $stmt_update->bindParam(':tasks_completed', $tasks_completed_json);
        $stmt_update->bindParam(':kpi_score', $kpi_score, PDO::PARAM_INT);
        $stmt_update->bindParam(':problem_description', $problem_description);
        $stmt_update->bindParam(':report_id', $existing_report['id'], PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'របាយការណ៍ KPI ប្រចាំថ្ងៃត្រូវបានកែប្រែដោយជោគជ័យ!', 'kpi_score' => $kpi_score]);
        } else {
            echo json_encode(['success' => false, 'message' => 'បរាជ័យក្នុងការកែប្រែរបាយការណ៍ KPI ប្រចាំថ្ងៃ។']);
        }
    } else {
        // Insert new report
        $stmt_insert = $pdo->prepare("INSERT INTO daily_kpi_reports (employee_id, report_date, tasks_completed, kpi_score, problem_description) VALUES (:employee_id, :report_date, :tasks_completed, :kpi_score, :problem_description)");
        $stmt_insert->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':report_date', $report_date);
        $stmt_insert->bindParam(':tasks_completed', $tasks_completed_json);
        $stmt_insert->bindParam(':kpi_score', $kpi_score, PDO::PARAM_INT);
        $stmt_insert->bindParam(':problem_description', $problem_description);

        if ($stmt_insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'របាយការណ៍ KPI ប្រចាំថ្ងៃត្រូវបានរក្សាទុកដោយជោគជ័យ!', 'kpi_score' => $kpi_score]);
        } else {
            echo json_encode(['success' => false, 'message' => 'បរាជ័យក្នុងការរក្សាទុករបាយការណ៍ KPI ប្រចាំថ្ងៃ។']);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
