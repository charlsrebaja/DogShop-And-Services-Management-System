<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email && $password) {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'Admin') {
                header('Location: ../admin/dashboard.php');
            } elseif ($user['role'] === 'Staff') {
                header('Location: staff/dashboard.php');
            } else {
                header('Location: index.php'); // For Customer
            }
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
     <!-- For High-Resolution Displays -->
     <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">

    <style>
        body {
            background-color: #04BACC !important; /* Custom color for the navbar */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.8s ease-in-out;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
            font-size: 32px;
        }
        .login-header p {
            color: #666;
            font-size: 16px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: none;
            padding: 12px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 5px rgba(0, 180, 216, 0.5);
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            background: #f68b45;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #0096c7;
        }
        .btn-secondary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            background: #6c757d;
            border: none;
            color: #fff;
            transition: background 0.3s ease;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        p.text-center {
            color: #555;
            margin-top: 20px;
            font-size: 14px;
        }
        a {
            text-decoration: none;
            color: #00b4d8;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }

        /* FadeIn animation */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Additional responsiveness */
        @media (max-width: 768px) {
            .login-card {
                padding: 20px;
            }
            .login-header h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2>Login</h2>
            <p>Log in to your account to continue</p>
        </div>
        <div class="card-body">
            <!-- Success message -->
            <?php if (isset($_GET['success']) && $_GET['success'] === 'registered') : ?>
                <div class="alert alert-success">Registration successful! Please log in.</div>
            <?php endif; ?>

            <!-- Error message -->
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form action="login.php" method="POST">
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-envelope-fill me-2" style="font-size: 1.5rem; color:black;"></i>
        <div class="flex-grow-1">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
    </div>
    
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-lock-fill me-2" style="font-size: 1.5rem; color: black;"></i>
        <div class="flex-grow-1">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Log In</button>
</form>

            <p class="mt-3 text-center">
                Don't have an account? <a href="register.php">Signup now</a>.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
