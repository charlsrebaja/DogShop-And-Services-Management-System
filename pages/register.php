<?php

include '../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            // Redirect to login page after successful registration
            header('Location: login.php?success=registered');
            exit;
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
     <!-- For High-Resolution Displays -->
     <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">

    <style>
        body {
            background: #158691 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-header h2 {
            color: #333;
        }
        .alert {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            box-shadow: none;
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
        .btn-primary:hover{
            background-color: #158691 !important;
        }
        p.text-center {
            color: #555;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h2>Signup</h2>
            <p>Create a new account</p>
        </div>
        <div class="card-body">
            <!-- Error message -->
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form action="register.php" method="POST">
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-person-fill me-2" style="font-size: 1.5rem; color: #666;"></i>
        <div class="flex-grow-1">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
        </div>
    </div>
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-envelope-fill me-2" style="font-size: 1.5rem; color: #666;"></i>
        <div class="flex-grow-1">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
    </div>
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-lock-fill me-2" style="font-size: 1.5rem; color: #666;"></i>
        <div class="flex-grow-1">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
    </div>
    <div class="form-group mb-3 d-flex align-items-center">
        <i class="bi bi-lock-fill me-2" style="font-size: 1.5rem; color: #666;"></i>
        <div class="flex-grow-1">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
<p class="mt-3 text-center">
    Already have an account? <a href="login.php">Log In</a>.
</p>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
