<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Handle image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_name = basename($_FILES['profile_image']['name']);
        $upload_dir = '../uploads/';
        $file_path = $upload_dir . $file_name;

        // Save the uploaded file
        if (move_uploaded_file($file_tmp, $file_path)) {
            $image_url = '../uploads/' . $file_name;

            // Update user with the image
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$username, $email, $image_url, $user_id]);
        }
    } else {
        // Update user without image
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);
    }

    $_SESSION['success'] = "Profile updated successfully!";
    header('Location: index.php'); // Redirect to index.php
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <!-- For High-Resolution Displays -->
     <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #38b3be, #a2ded0);
            font-family: 'Arial', sans-serif;
            color: #444;
        }

        .profile-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            position: relative;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .profile-header p {
            font-size: 14px;
            color: #666;
        }

        .profile-image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #38b3be;
            transition: transform 0.3s ease;
        }

        .profile-image:hover {
            transform: scale(1.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
            transition: box-shadow 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 8px rgba(56, 179, 190, 0.5);
        }

        .btn-primary {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
            background-color: #ff7b23;
            color: white;
        }

        .btn-primary:hover {
            background-color: #f07727;
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            font-size: 14px;
            text-align: center;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label input {
            padding: 10px 10px 10px 30px;
        }

        .floating-label i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h2>Update Profile</h2>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Display profile image -->
        <div class="profile-image-container">
            <img src="<?= $user['image_url'] ? $user['image_url'] : '../uploads/default-profile.png'; ?>" alt="Profile Image" class="profile-image">
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="floating-label">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" required placeholder="Username">
            </div>
            <div class="floating-label">
                <i class="fas fa-envelope"></i>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>" required placeholder="Email">
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
