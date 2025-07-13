<?php
// php/get_daily_kpi_reports.php
// Fetches aggregated daily KPI reports (total KPI score per employee) with associated employee details.
// Accessible only by 'HR' role.

header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated. Please log in.']);
    exit();
}

$session_role = $_SESSION['role'] ?? 'unknown';

// Convert session role to lowercase and trim whitespace for robust case-insensitive comparison
if (strtolower(trim($session_role)) !== 'hr') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access. Your current role (' . htmlspecialchars($session_role) . ') does not have permission. Only HR role can view daily KPI reports.'
    ]);
    exit();
}

try {
    // Modified SQL query to get total KPI score per employee
    $stmt = $pdo->query("
        SELECT
            e.id AS employee_id,
            e.name AS employee_name,
            e.position AS employee_position,
            e.contact AS employee_contact,
            COALESCE(SUM(dkr.kpi_score), 0) AS total_kpi_score -- Sum KPI scores, default to 0 if no reports
        FROM
            employees e
        LEFT JOIN -- Use LEFT JOIN to include employees even if they have no KPI reports
            daily_kpi_reports dkr ON e.id = dkr.employee_id
        GROUP BY
            e.id, e.name, e.position, e.contact
        ORDER BY
            e.name ASC
    ");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'reports' => $reports]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
