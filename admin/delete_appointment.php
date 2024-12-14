<?php
include '../config/db.php';

// Debugging: Print the appointment ID
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Debugging: Output the ID received
    echo "Attempting to delete appointment with ID: " . htmlspecialchars($appointment_id);

    // Prepare the DELETE query
    $query = "DELETE FROM appointments WHERE id = :id";
    $stmt = $pdo->prepare($query);

    // Bind the appointment ID
    $stmt->bindValue(':id', $appointment_id, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Debugging: Print success message
        echo "Appointment deleted successfully.";
        header('Location: dashboard.php#manage_appointments.php');
        exit;
    } else {
        // If deletion failed, show an error message
        echo "Error deleting appointment.";
    }
} else {
    // If no ID is passed, show an error message
    echo "No appointment ID specified.";
}
?>
