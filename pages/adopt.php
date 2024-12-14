<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to adopt a dog.";
    exit;
}

// Validate the 'id' parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dog_id = $_GET['id'];

    // Fetch the dog details
    $stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ?");
    $stmt->execute([$dog_id]);
    $dog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dog) {
        echo "Dog not found in the database. Please try again.";
        exit;
    }
} else {
    echo "No valid dog ID provided. Please go back and select a dog.";
    exit;
}

// Handle adoption request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['user_id'];
    $adoption_date = date('Y-m-d');
    $status = 'Pending';

    $stmt = $pdo->prepare("INSERT INTO adoptions (dog_id, customer_id, adoption_date, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dog_id, $customer_id, $adoption_date, $status]);

    echo "<script>alert('Adoption request submitted successfully!'); window.location.href = 'index.php';</script>";
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt <?php echo htmlspecialchars($dog['name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Adopt <?php echo htmlspecialchars($dog['name']); ?></h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo htmlspecialchars($dog['name']); ?></h4>
            <p><strong>Breed:</strong> <?php echo htmlspecialchars($dog['breed']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($dog['age']); ?> years</p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($dog['gender']); ?></p>
            <p><strong>Adoption Fee:</strong> $<?php echo htmlspecialchars($dog['adoption_fee']); ?></p>
            <form method="POST">
                <button type="submit" class="btn btn-success">Submit Adoption Request</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
