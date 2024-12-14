<?php
session_start();
include '../config/db.php';

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : '';
$admin_image = isset($_SESSION['admin_image']) ? $_SESSION['admin_image'] : '';

// Handle profile update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'];
    $new_image = $_FILES['image'];

    // Handle image upload
    if ($new_image['error'] === 0) {
        $image_path = '../uploads/' . basename($new_image['name']);
        move_uploaded_file($new_image['tmp_name'], $image_path);
    } else {
        $image_path = $_SESSION['admin_image']; // Keep old image if none is uploaded
    }

    // Update the admin's name and image in the database
    $query = "UPDATE admins SET name = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$new_name, $image_path, $_SESSION['admin_id']]);

    // Update session variables
    $_SESSION['admin_name'] = $new_name;
    $_SESSION['admin_image'] = $image_path;

    // Redirect to the dashboard after the update
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- For High-Resolution Displays -->
     <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>
        body {
            background: linear-gradient(135deg, #38b3be, #a2ded0);
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #90caf9;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .btn-primary {
            background-color: #2196f3;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1976d2;
        }
        .form-label {
            font-weight: bold;
            color: #0d47a1;
        }
        .spinner-border {
            display: none;
        }
        .loading .spinner-border {
            display: inline-block;
        }
        .loading .btn-primary {
            pointer-events: none;
            opacity: 0.7;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center mb-3" style="color: #0d47a1;">Update Profile</h2>
            <form action="update_profile.php" method="post" enctype="multipart/form-data" id="updateForm">
                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin_name); ?>" required>
                </div>
                <div class="mb-2">
                    <label for="image" class="form-label">Profile Image</label>
                    <?php if ($admin_image): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($admin_image); ?>" alt="Current Profile Image" class="img-thumbnail" width="100">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.getElementById('updateForm');
        form.addEventListener('submit', function() {
            const button = form.querySelector('button');
            button.classList.add('loading');
        });
    </script>
</body>
</html>
