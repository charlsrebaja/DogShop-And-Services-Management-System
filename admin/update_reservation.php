<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE reservations SET status = '$new_status' WHERE id = $reservation_id";

    if ($conn->query($sql) === TRUE) {
        echo "Reservation updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
