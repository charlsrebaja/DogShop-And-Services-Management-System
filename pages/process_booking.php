<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];
    $date = $_POST['date'];
    $customer_id = 1; // Replace with logged-in customer's ID

    try {
        // Insert booking into reservations table
        $query = "INSERT INTO reservations (service_id, staff_id, date, customer_id)
                  VALUES (:service_id, :staff_id, :date, :customer_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'service_id' => $service_id,
            'staff_id' => $staff_id,
            'date' => $date,
            'customer_id' => $customer_id
        ]);

        // Optional: Update staff availability
        $update_query = "UPDATE staff SET availability = 0 WHERE id = :staff_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute(['staff_id' => $staff_id]);

        echo "Service booked successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
