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
    <title>Vaccine & Pharmacy Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap CSS -->
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Bundle JS (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .table-header {
            background-color: #158691; /* Dark Orange */
            color: white;
            text-align: center;
        }
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
    .healthcare-banner {
    background-color: #04BACC; /* Matches the site's theme color */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
    width: 75%;
    margin: auto;

}
.healthcare-banner .btn{
    background-color:#04BACC !important;
    color:white;
    
}
.healthcare-banner .btn:hover{
     color: white;
}
.btn:hover{
    background-color: #158691 !important;
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
     <div id="healthcareBannerCarousel" class="carousel slide text-center text-white " data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#healthcareBannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#healthcareBannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>

    <!-- Carousel Inner -->
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: url('../healthcarebanner1.jpg'); background-size: cover; background-position: center; height: 400px;">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <h1>You Can Now Book Your Appointment</h1>
                <p class="mt-3">Get the best care for your furry friends by scheduling an appointment today!</p>
                <a href="appointment.php" class="btn px-4 py-2 mt-3 text-white" style="font-weight: bold; border-radius: 5px; background-color: #04BACC;">
                    Schedule an Appointment
                </a>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('../healthcarebanner2.jpg'); background-size: cover; background-position: center; height: 400px;">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Your Dog Deserves the Best Care</h1>
                <p class="mt-3">Make an appointment with our expert staff today.</p>
                <a href="appointment.php" class="btn px-4 py-2 mt-3 text-white" style="font-weight: bold; border-radius: 5px; background-color: #04BACC;">
                    Schedule an Appointment
                </a>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#healthcareBannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#healthcareBannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


    <div class="container mt-5">
        <h2 class="text-center mb-4">Vaccine & Pharmacy</h2>
        <table class="table table-bordered table-sm" style="font-size: 14px;">
            <thead class="table-header" style="color: white;">
                <tr>
                    <th>Service</th>
                    <th>Price (PHP)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Deworming 5kg below Tablet</td>
                    <td>190.00</td>
                </tr>
                <tr>
                    <td>Deworming 6kg to 10kg</td>
                    <td>300.00</td>
                </tr>
                <tr>
                    <td>Deworming 11kg to 20kg Tablet</td>
                    <td>400.00</td>
                </tr>
                <tr>
                    <td>Deworming 21kg to 30kg Tablet</td>
                    <td>500.00</td>
                </tr>
                <tr>
                    <td>Tricat Vaccine</td>
                    <td>1,200.00</td>
                </tr>
                <tr>
                    <td>Vaccine 6-in-1</td>
                    <td>950.00</td>
                </tr>
                <tr>
                    <td>Vaccine Kennel Cough</td>
                    <td>600.00</td>
                </tr>
                <tr>
                    <td>RCCP Vaccine</td>
                    <td>1,000.00</td>
                </tr>
                <tr>
                    <td>Proheart Injection 5kg below</td>
                    <td>1,500.00</td>
                </tr>
                <tr>
                    <td>Proheart Injection 6kg to 10kg</td>
                    <td>2,500.00</td>
                </tr>
                <tr>
                    <td>Proheart Injection 11kg to 15kg</td>
                    <td>3,500.00</td>
                </tr>
                <tr>
                    <td>Proheart Injection 16kg to 20kg</td>
                    <td>4,500.00</td>
                </tr>
                <tr>
                    <td>Antibiotic Injection 5kg below</td>
                    <td>300.00</td>
                </tr>
                <tr>
                    <td>Antibiotic Injection 6kg to 10kg</td>
                    <td>350.00</td>
                </tr>
                <tr>
                    <td>Antibiotic Injection 11kg to 20kg</td>
                    <td>450.00</td>
                </tr>
                <tr>
                    <td>Antibiotic Injection 21kg to 30kg</td>
                    <td>600.00</td>
                </tr>
                <tr>
                    <td>Multivitamin Injection 5kg below</td>
                    <td>300.00</td>
                </tr>
                <tr>
                    <td>Multivitamin Injection 6kg to 10kg</td>
                    <td>400.00</td>
                </tr>
                <tr>
                    <td>Multivitamin Injection 11kg to 20kg</td>
                    <td>600.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
<footer class="bg-custome text-white py-3">
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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>