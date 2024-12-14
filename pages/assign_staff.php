<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];

    try {
        $query = "INSERT INTO service_staff (service_id, staff_id) VALUES (:service_id, :staff_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['service_id' => $service_id, 'staff_id' => $staff_id]);

        echo "Staff assigned successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
