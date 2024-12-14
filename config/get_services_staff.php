<?php
include '../config/db.php';

$stmtServices = $pdo->query("SELECT service_name FROM services");
$services = $stmtServices->fetchAll(PDO::FETCH_ASSOC);

$stmtStaff = $pdo->query("SELECT id, name FROM staff");
$staff = $stmtStaff->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['services' => $services, 'staff' => $staff]);
?>