<?php
session_start();
include '../config/db.php'; // Include database connection

// Ensure booking details exist in the session
if (!isset($_SESSION['booking_details'])) {
    die("Invalid booking request. Please start again.");
}

// Retrieve booking details from the session
$booking = $_SESSION['booking_details'];
$service_id = $booking['service_id'];
$date = $booking['date'];
$time = $booking['time'];

// Fetch the staff members for the selected service
$query = "
    SELECT s.id, s.name, s.expertise, s.availability 
    FROM staff s 
    JOIN staff_services ss ON s.id = ss.staff_id 
    WHERE ss.service_id = :service_id
      AND s.availability = 'available'
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
$stmt->execute();
$staffMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$staffMembers) {
    die("No staff members are available for this service.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Staff</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Select a Staff Member</h1>
        <p class="text-center">For: <strong><?= $booking['date'] ?> at <?= $booking['time'] ?></strong></p>

        <form action="confirm_booking.php" method="POST">
            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            <input type="hidden" name="date" value="<?= $date ?>">
            <input type="hidden" name="time" value="<?= $time ?>">

            <div class="form-group">
                <label for="staff">Choose a staff member:</label>
                <select class="form-control" id="staff" name="staff_id" required>
                    <option value="" disabled selected>Select a staff member</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?= $staff['id'] ?>">
                            <?= $staff['name'] ?> - <?= $staff['expertise'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Proceed to Confirmation</button>
        </form>
    </div>
</body>
</html>
