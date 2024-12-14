<?php
session_start();
include '../config/db.php'; // Include database connection

// Get dog ID from the URL
if (!isset($_GET['dog_id']) || empty($_GET['dog_id'])) {
    header('Location: index.php'); // Redirect to home if no dog ID is provided
    exit;
}

$dog_id = $_GET['dog_id'];

// Fetch the dog's details from the database
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ?");
$stmt->execute([$dog_id]);
$dog = $stmt->fetch(PDO::FETCH_ASSOC);

// If no dog is found, redirect back
if (!$dog) {
    header('Location: index.php');
    exit;
}

// Check if the dog is adopted
$is_adopted = ($dog['status'] === 'Adopted');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            color: #333; /* Standard text color for contrast */
            background-color: #f1f1f1 !important;
        }

        .dog-details {
            max-width: 800px;
            margin: 50px auto;
        }

        .dog-image {
            max-height: 450px;
            object-fit: cover;
            width: 100%;
        }

        /* Footer */
        footer {
            background: #158691 !important;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 0.9rem;
        }

        footer a {
            color: #dce7f7;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
        .unavailable-message {
        background-color: #D32F2F; /* Dark Orange-Red */
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease-in-out, shake 0.5s ease-in-out infinite;
        max-width: 300px;
        margin: 10px auto;
        text-align: center;
    }

/* Animation to fade in the message */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation to create a shake effect */
@keyframes shake {
    0% {
        transform: translateX(-5px);
    }
    15% {
        transform: translateX(5px);
    }
    40% {
        transform: translateX(-5px);
    }
    65% {
        transform: translateX(5px);
    }
    80% {
        transform: translateX(0);
    }
}



    </style>
</head>
<body>

<?php include 'navbar.php'; ?>  

<div class="container dog-details my-5">
    <div class="row align-items-center">
        <!-- Dog Image -->
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="../dogimg/<?php echo htmlspecialchars($dog['image_url']); ?>" class="img-fluid dog-image rounded shadow" alt="Dog Image">
        </div>

        <!-- Dog Details and Description -->
        <div class="col-md-6">
            <div class="description-box p-4 border rounded shadow">
                <h1 class="mb-3"><?php echo htmlspecialchars($dog['name']); ?></h1>
                <p><strong>Breed:</strong> <?php echo htmlspecialchars($dog['breed']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($dog['age']); ?> years</p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($dog['gender']); ?></p>
                <p><strong>Adoption Fee:</strong> P<?php echo htmlspecialchars($dog['adoption_fee']); ?></p>
                <hr>
                <p><strong>Description:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($dog['description'])); ?></p>
                
                <?php if ($is_adopted): ?>
                    <div class="unavailable-message">
                        This dog has already been adopted and is no longer available for adoption.
                    </div>
                <?php else: ?>
                    <div class="mt-4">
                        <a href="../pages/adoption_form.php?dog_id=<?php echo $dog_id; ?>" class="btn btn-custome me-2">Adopt This Dog</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
