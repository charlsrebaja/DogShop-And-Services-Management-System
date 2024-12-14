<?php
session_start();
include '../config/db.php';

// Assuming the admin info is stored in the session
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
$admin_image = isset($_SESSION['admin_image']) && !empty($_SESSION['admin_image']) ? $_SESSION['admin_image'] : 'default-avatar.jpg';


// Fetch total dogs
$queryDogs = "SELECT COUNT(*) AS total FROM dogs"; // Replace 'dogs' with the correct table name
$stmtDogs = $pdo->prepare($queryDogs);
$stmtDogs->execute();
$rowDogs = $stmtDogs->fetch(PDO::FETCH_ASSOC);
$total_dogs = $rowDogs['total'];

// Fetch total users
$queryUsers = "SELECT COUNT(*) AS total FROM users";
$stmtUsers = $pdo->prepare($queryUsers);
$stmtUsers->execute();
$rowUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC);
$total_users = $rowUsers['total'];

// Fetch total orders
$queryOrders = "SELECT COUNT(*) AS total FROM orders";
$stmtOrders = $pdo->prepare($queryOrders);
$stmtOrders->execute();
$rowOrders = $stmtOrders->fetch(PDO::FETCH_ASSOC);
$total_orders = $rowOrders['total'];

// Fetch total products
$queryProducts = "SELECT COUNT(*) AS total FROM products";  // Ensure 'products' is the correct table name
$stmtProducts = $pdo->prepare($queryProducts);
$stmtProducts->execute();
$rowProducts = $stmtProducts->fetch(PDO::FETCH_ASSOC);
$total_products = $rowProducts['total'];


// Fetch all staff members
$query = "SELECT * FROM staff";
$stmt = $pdo->query($query);
$staff_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total appointment requests
$queryAppointments = "SELECT COUNT(*) AS total FROM appointments"; // Replace 'appointments' with your actual table name
$stmtAppointments = $pdo->prepare($queryAppointments);
$stmtAppointments->execute();
$rowAppointments = $stmtAppointments->fetch(PDO::FETCH_ASSOC);
$total_appointments = $rowAppointments['total'];


// Fetch total pending appointment requests
$queryPendingAppointments = "SELECT COUNT(*) AS total FROM appointments WHERE status = 'pending'";
$stmtPendingAppointments = $pdo->prepare($queryPendingAppointments);
$stmtPendingAppointments->execute();
$rowPendingAppointments = $stmtPendingAppointments->fetch(PDO::FETCH_ASSOC);
$total_pending_appointments = $rowPendingAppointments['total'];

// Fetch total pending orders
$queryPendingOrders = "SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'";
$stmtPendingOrders = $pdo->prepare($queryPendingOrders);
$stmtPendingOrders->execute();
$rowPendingOrders = $stmtPendingOrders->fetch(PDO::FETCH_ASSOC);
$total_pending_orders = $rowPendingOrders['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" href="../uploads/logo (2).png" type="image/x-icon">

</head>
<body>
             <div class="container-fluid">
                <div class="row">
                                
             <nav class="col-md-2 d-md-block sidebar">
                                <div class="admin-profile text-center">
                                    <!-- Admin Profile Section -->
                                    <a href="update_profile.php">
                                        <img src="../uploads/<?php echo htmlspecialchars($admin_image); ?>" alt="Admin Image" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                                    </a>
                                      <h5 class="mt-2"><?php echo htmlspecialchars($admin_name); ?></h5>
                                </div>
                <ul>
                    <li><a href="#manage-users"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><a href="#manage-products"><i class="fas fa-box"></i> Manage Products</a></li>
                    <li><a href="#manage-dogs"><i class="fas fa-dog"></i> Manage Dogs</a></li>
                    <li><a href="#manage-services"><i class="fas fa-concierge-bell"></i> Manage Services</a></li>
                    <li><a href="#manage_adoption_request"><i class="fas fa-heart"></i> Manage Adoption</a></li>
                    <li><a href="#order_management"><i class="fas fa-box"></i> Order Management</a></li>
                    <li><a href="#manage_appointment"><i class="fas fa-calendar-alt"></i> Manage Appointments</a></li>
                    <li><a href="#manage_staff"><i class="fas fa-users"></i> Manage Staff</a></li>
                    <li><a href="../pages/login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>

             <!-- Main Content -->
             <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="main-content">
                    <!-- Statistics Cards -->
                    <section class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-custome text-white">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                <i class="fas fa-dog" style="font-size: 2rem; color: #1a0d04;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Total Dogs</h5>
                                    <p class="card-text"><?php echo $total_dogs; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-custome text-white">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-people" style="font-size: 2rem; color:#1a0d04;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text"><?php echo $total_users; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-custome text-white">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-cart-check" style="font-size: 2rem; color:#1a0d04 ;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text"><?php echo $total_orders; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-custome text-white">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-box" style="font-size: 2rem; color: #1a0d04;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text"><?php echo $total_products; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 py-2">
                    <div class="card bg-custome text-white">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-calendar-check" style="font-size: 2rem; color: #1a0d04;"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Appointment Requests</h5>
                                <p class="card-text"><?php echo $total_appointments; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 py-2">
                    <div class="card bg-custome text-white">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-calendar-check" style="font-size: 2rem; color:#1a0d04;"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Pending Appointments</h5>
                                <p class="card-text"><?php echo $total_pending_appointments; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 py-2">
                    <div class="card bg-custome text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-clock-history" style="font-size: 2rem; color:#1a0d04;"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Pending Orders</h5>
                                <p class="card-text"><?php echo $total_pending_orders; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                </section>

                    
                <section id="manage-users" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_users.php'; ?>
                    </div>
                </section>

                <section id="manage-products" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_products.php'; ?>
                    </div>
                </section>
                   
                <section id="manage-dogs" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_dogs.php'; ?>
                    </div>
                </section>


                <section id="manage_adoption_request" class="my-4">
                    <div class="table-responsive">
                        <?php include 'adoption_requests.php'; ?>
                    </div>
                </section>

                <section id="order_management" class="my-4">
                    <div class="table-responsive">
                        <?php include 'order_management.php'; ?>
                    </div>
                </section>

                <section id="manage_appointment" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_appointment.php'; ?>
                    </div>
                </section>

                <section id="manage_staff" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_staff.php'; ?>
                    </div>
                </section>

                <section id="manage-services" class="my-4">
                    <div class="table-responsive">
                        <?php include 'manage_services.php'; ?>
                    </div>
                </section>

                

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
