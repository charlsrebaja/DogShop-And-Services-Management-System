<?php
include '../config/db.php'; // Include database connection

// Fetch the selected service details
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $query = "SELECT * FROM services WHERE id = :service_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service not found.");
    }
} else {
    die("Invalid request.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Book a Service</h1>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title"><?= $service['service_name'] ?></h2>
                <p class="card-text"><?= $service['description'] ?></p>
                <p><strong>Price:</strong> $<?= $service['price'] ?></p>
                <p><strong>Duration:</strong> <?= $service['duration'] ?> minutes</p>
                <form action="confirm_booking.php" method="POST">
                    <!-- Pass service details to the next step -->
                    <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                    <div class="form-group">
                        <label for="date">Select Date:</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Select Time:</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <button type="submit" class="btn btn-success">Proceed to Staff Selection</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
