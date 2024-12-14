<?php
// Include database connection
include '../config/db.php'; // Include your database connection


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];

    // Remove existing staff assignments for this service
    $delete_query = "DELETE FROM service_staff WHERE service_id = :service_id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([':service_id' => $service_id]);

    // Assign the new staff to the service
    $assign_query = "INSERT INTO service_staff (service_id, staff_id) VALUES (:service_id, :staff_id)";
    $stmt = $pdo->prepare($assign_query);
    $stmt->execute([
        ':service_id' => $service_id,
        ':staff_id' => $staff_id
    ]);

    // Redirect back to the services management page with a success message
    header('Location: dashboard.php#manage_services?success=1');
    exit();
}
?>
