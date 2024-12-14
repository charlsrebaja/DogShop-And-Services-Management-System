<?php
include '../config/db.php';

// Check if ID is set in the GET parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='text-danger'>Invalid request. User ID is missing.</div>";
    exit;
}

$user_id = $_GET['id'];

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='text-danger'>User not found.</div>";
    exit;
}

// Check for POST request (Update logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $image_url = $user['image_url']; // Keep original image URL by default

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $image_name;
        } else {
            echo "<div class='text-danger'>Failed to upload image.</div>";
            exit;
        }
    }

    // Update user in the database
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$username, $email, $role, $image_url, $user_id]);

    // Redirect back to the manage user page
    header("Location: dashboard.php#manage_user.php");
    exit;
}

// If no POST, output the form for AJAX
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dog Details</title>
    <!-- Bootstrap and FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body>
<form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" 
               value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="role">Role</label>
        <select name="role" id="role" class="form-control">
            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
            <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Profile Image</label><br>
        <?php if (!empty($user['image_url'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($user['image_url']); ?>" 
                 alt="User Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"><br>
        <?php endif; ?>
        <input type="file" name="image" id="image" class="form-control-file">
    </div>
    <button type="submit" class="btn btn-primary mt-3">Update User</button>
</form>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
