<?php
  include 'cart.php'; // For cart page

  // Fetch unread notifications (is_read = 0)
$notificationStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$notificationStmt->execute([$user_id]);
$unreadNotifications = $notificationStmt->fetchColumn();
?>
<style>
    /* Navbar Styling */
.navbar {
    background: linear-gradient( #00CCFF); /* Gradient background */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.custom-bg {
    background-color: #04BACC !important; /* Custom color for the navbar */
}

.navbar-brand {
    font-size: 1.5rem;
    color: #fff;
    text-transform: uppercase;
}

.navbar-nav .nav-link {
    font-size: 1rem;
    margin: 0 10px;
    color: #fff;
    text-decoration: none; /* Ensures no underline */
    transition: color 0.3s ease-in-out;
}

.navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
    color: #ffd700; /* Gold for active or hover state */
    text-decoration: none; /* Ensures no underline even on hover or active */
}

.cart-icon {
    color: #fff;
    font-size: 1.3rem;
}

.cart-badge {
    font-size: 0.8rem;
    background: #f0b30d;
    color: white;
}

.notification-icon {
    color: #fff;
    font-size: 1.3rem;
    margin-right: 15px;
}

.notification-badge {
    font-size: 0.8rem;
    background-color: #f68b45 !important;

    color: white;
}

.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.dropdown-menu .dropdown-item:hover {
    background-color: #5068a9;
    color: white;
}

.custome-cart {
    background-color: #f68b45 !important;
}

#notificationIcon{
    margin-left: 10px;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark custom-bg sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">Doggobox</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center"> <!-- ms-auto pushes the nav items to the right -->
                <?php
                // Get the current file name
                $currentPage = basename($_SERVER['PHP_SELF']);
                ?>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'product_shop.php' ? 'active' : '' ?>" href="product_shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'appointment.php' ? 'active' : '' ?>" href="appointment_form.php">Appointment</a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'dog.php' ? 'active' : '' ?>" href="dog.php">Adopt</a>
                </li>

              

                <!-- Cart Icon Button -->
                <li class="nav-item position-relative">
                    <div id="cartIcon" class="cart-icon position-relative d-flex align-items-center" onclick="toggleCart()" style="cursor: pointer;">
                        <i class="bi bi-cart" style="font-size: 1.5rem; color: white;"></i> <!-- Bootstrap cart icon -->
                        <?php if (!empty($cart)): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custome-cart cart-badge">
                                <?php echo count($cart); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </li>

<!-- Notification Icon Button -->
<li class="nav-item position-relative">
    <a id="notificationIcon" class="notification-icon position-relative d-flex align-items-center" href="notifications.php" style="cursor: pointer;">
        <i class="bi bi-bell" style="font-size: 1.5rem; color: white;"></i> <!-- Notification bell icon -->
        <?php if ($unreadNotifications > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notification-badge">
                <?php echo $unreadNotifications; ?>
            </span>
        <?php endif; ?>
    </a>
</li>



<!-- Profile Icon Link -->
<?php if (isset($_SESSION['user_id'])): ?>
    <?php
    // Fetch user details
    include '../config/db.php';
    $stmt = $pdo->prepare("SELECT image_url FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Default image if not set
    $profileImage = $user['image_url'] ?? '../uploads/default-profile.png';
    ?>
    <li class="nav-item">
        <a class="nav-link" href="profile.php">
            <img src="<?= $profileImage; ?>" alt="Profile Image" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
        </a>
    </li>
<?php endif; ?>

<!-- Login/Logout Link -->
<?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'logout.php' ? 'active' : '' ?>" href="logout.php">Logout</a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    </li>
<?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
