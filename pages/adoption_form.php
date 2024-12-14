<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['dog_id']) || empty($_GET['dog_id'])) {
    header('Location: index.php');
    exit;
}

$dog_id = $_GET['dog_id'];
if (!isset($pdo)) {
    die("Database connection not established.");
}

$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ?");
$stmt->execute([$dog_id]);
$dog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dog) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['user_id'];
    $adoption_date = date('Y-m-d');
    $status = 'Pending';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];

    $target_dir = "../uploads/ids/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $valid_id_name = basename($_FILES["valid_id"]["name"]);
    $valid_id_tmp = $_FILES["valid_id"]["tmp_name"];
    $valid_id_target = $target_dir . $valid_id_name;

    if (move_uploaded_file($valid_id_tmp, $valid_id_target)) {
        $stmt = $pdo->prepare("INSERT INTO adoptions (dog_id, customer_id, name, email, address, contact_number, valid_id, adoption_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$dog_id, $customer_id, $name, $email, $address, $contact_number, $valid_id_name, $adoption_date, $status]);

        echo "<script>alert('Adoption request submitted successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error uploading valid ID. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt <?php echo htmlspecialchars($dog['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
          body {
            font-family: Arial, sans-serif;
            background-image: url(../uploads/dogadopbg.jpg);
            background-repeat: no-repeat;
            background-size: cover; /* Ensures the image covers the entire background */
            background-attachment: fixed; /* Keeps the background fixed while scrolling */
            color: #333;
        }

        .form-container {
            max-width: 635px;
            margin: auto;
            padding: 20px;
        }
        .form-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 15px;
        }
        .btn-sm {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container py-4">

        
    <div class="form-container">
        <div class="card mb-2" style="background-color: #158691; height: 115px; display: flex; justify-content: center; align-items: center; border-radius: 8px;">
            <h4 class="text-center" style="color:white; font-weight: bold; margin: 0;">Adopt Your New Best Friend Today!</h4>
        </div>

        <div class="form-section">
            <h3 class="text-center mb-4">Adoption Form</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="valid_id" class="form-label">Upload ID</label>
                    <input type="file" name="valid_id" id="valid_id" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    <a href="dog_details.php?dog_id=<?php echo $dog_id; ?>" class="btn btn-secondary btn-sm">Cancel</a>
                </div>
            </form>
        </div>

        <div class="form-section">
            <h5>Adoption Process</h5>
            <ol style="font-size: 0.9rem; line-height: 1.5;">
                <li>Choose a dog to adopt.</li>
                <li>Complete the form and upload ID.</li>
                <li>Application review and meet the dog.</li>
                <li>Finalize the adoption process.</li>
            </ol>
        </div>

        <div class="form-section">
            <h5>Eligibility Checklist</h5>
            <ul style="font-size: 0.9rem; line-height: 1.5;">
                <li>18+ years old</li>
                <li>Stable home environment</li>
                <li>Willing to provide long-term care</li>
            </ul>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
