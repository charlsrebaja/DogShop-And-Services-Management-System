<?php
session_start();
include '../config/db.php'; // Include database connection

// Fetch featured dogs for adoption (limit to top 10 featured dogs)
$featured_dogs_stmt = $pdo->query("SELECT * FROM dogs ORDER BY adoption_fee DESC LIMIT 20");
$featured_dogs = $featured_dogs_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Dog Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- For High-Resolution Displays -->
    <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">

    <style>
        body {
            background-color: #f1f1f1 !important;
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }
        .section-title {
            color:black; /* Highlight color from your palette */
            font-size: 22px;
        }
        .card-img-container {
            width: 100%;
            height: 250px;
            overflow: hidden;
        }
        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 200px;

            }    /* Footer */
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
        
    </style>
</head>
<body>
    <?php
    include 'navbar.php'; // Include the navbar
    ?>  
<div class="container my-5">
    <h4 class="section-title mb-4">Featured Dogs for Adoption</h4>
    <div class="row g-4">
        <?php foreach ($featured_dogs as $dog): ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-img">
                        <a href="dog_details.php?dog_id=<?php echo $dog['id']; ?>">
                            <img src="../dogimg/<?php echo htmlspecialchars($dog['image_url']); ?>" alt="Dog Image" class="img-fluid" style="border-radius: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <?php include 'footer.php'; ?>
</body>
</html>
