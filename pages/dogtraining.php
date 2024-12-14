<?php
session_start();
include '../config/db.php'; // Include database connection
// Fetch some products (limit to top 3 most popular)
$products_stmt = $pdo->query("SELECT * FROM products ORDER BY stock DESC LIMIT 10");
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <!-- Bootstrap Bundle JS (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <style>
             /* Footer */
    footer {
        background: #158691 !important;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 200px;
        font-size: 0.9rem;
    }

    footer a {
        color: #dce7f7;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }

    
.appointment-banner {
    background-color: #04BACC; /* Matches the site's theme color */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
    width: 75%;
    margin: auto;

}

.appointment-banner .btn{
    background-color:#f68b45 !important;
    color:white;
    
}
.appointment-banner .btn:hover{
     color: white;
}
.btn:hover{
    background-color:#158691 !important;
    transform: scale(1.05);
}
  .container .table{
     width: 60%;
     margin: auto;
}
  .text{
    width: 60%;
    margin: auto;
    font-size: 16px;
  }

</style>

</head>

<body>
    <?php
      include 'navbar.php'; // Include the navbar
    ?>

   <!-- Banner Section -->
<div id="dogTrainerCarousel" class="carousel slide text-center text-white" data-bs-ride="carousel" data-bs-interval="3000">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#dogTrainerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#dogTrainerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>

    <!-- Carousel Items -->
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active" style="background-image: url('../trainerbanner1.jpg'); background-size: cover; background-position: center; height: 400px;">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Meet Our Expert Trainers</h1>
                <p class="mt-3">Personalized training tailored to your dog's unique needs.</p>
                <a href="appointment.php" class="btn px-4 py-2 mt-3 text-white" style="font-weight: bold; border-radius: 5px; background-color: #04BACC;">
                    Schedule an Appointment
                </a>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item" style="background-image: url('../trainerbanner2.jpg'); background-size: cover; background-position: center; height: 400px;">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Basic Obedience Classes</h1>
                <p class="mt-3">Start your dog's journey to being well-mannered and disciplined.</p>
                <a href="appointment.php" class="btn px-4 py-2 mt-3 text-white" style="font-weight: bold; border-radius: 5px; background-color: #04BACC;">
                    Schedule an Appointment
                </a>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dogTrainerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dogTrainerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>








<!-- Footer Section -->
<footer class=" text-white py-3">
    <div class="container">
        <div class="row">
            <!-- Contact Section -->
            <div class="col-md-4 mb-3">
                <h6>Contact Us</h6>
                <ul class="list-unstyled mb-2 small">
                    <li><i class="bi bi-envelope"></i> info@dogshop.com</li>
                    <li><i class="bi bi-phone"></i> +123 456 7890</li>
                    <li><i class="bi bi-geo-alt"></i> 123 Dog Street, Dogtown</li>
                </ul>
            </div>

            <!-- About Section -->
            <div class="col-md-4 mb-3">
                <h6>About Us</h6>
                <p class="small mb-2">We are a passionate team dedicated to finding loving homes for dogs. Our mission is to provide a safe and caring environment for dogs and owners alike.</p>
            </div>

            <!-- Social Media Section -->
            <div class="col-md-4 mb-3">
                <h6>Follow Us</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-white"><i class="bi bi-facebook"></i> Facebook</a></li>
                    <li><a href="#" class="text-white"><i class="bi bi-twitter"></i> Twitter</a></li>
                    <li><a href="#" class="text-white"><i class="bi bi-instagram"></i> Instagram</a></li>
                    <li><a href="#" class="text-white"><i class="bi bi-youtube"></i> YouTube</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-2">
            <p class="small">&copy; 2024 Dog Shop. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>