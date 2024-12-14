<?php
session_start();
include '../config/db.php'; // Include database connection

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE appointments SET status = :status WHERE id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':appointment_id' => $appointmentId
    ]);

    // Redirect back to the dashboard
    header("Location: dashboard.php#manage_appointment");
    exit();
}
?>
