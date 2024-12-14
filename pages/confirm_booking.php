<?php
session_start();
include '../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch POST data
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $staff_id = $_POST['staff_id'];

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized access. Please log in to proceed.");
    }

    $user_id = $_SESSION['user_id']; // Retrieve user ID from session

    // Validate input
    if (empty($service_id) || empty($date) || empty($time) || empty($staff_id)) {
        die("Invalid input. Please fill out all fields.");
    }

    try {
        // Save booking to the database
        $stmt = $conn->prepare("
            INSERT INTO reservations (user_id, service_id, staff_id, reservation_date, reservation_time, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiiss", $user_id, $service_id, $staff_id, $date, $time);

        if ($stmt->execute()) {
            // Save to session for potential further use
            $_SESSION['booking_details'] = [
                'service_id' => $service_id,
                'staff_id' => $staff_id,
                'date' => $date,
                'time' => $time,
            ];

            // Redirect to a confirmation or success page
            header("Location: booking_confirmation.php?success=1");
            exit;
        } else {
            throw new Exception("Error while saving booking: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error and display an error message
        error_log($e->getMessage());
        die("An error occurred while processing your booking. Please try again later.");
    }
} else {
    // Invalid request method
    die("Invalid request method.");
}
?>
