<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $adoption_fee = $_POST['adoption_fee'];
    $status = $_POST['status'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp_name, "../dogimg/" . $image_name);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO dogs (name, breed, adoption_fee, image_url, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $breed, $adoption_fee, $image_name, $status]);

        // Set session message
        $_SESSION['message'] = "Dog added successfully!";

    header("Location: dashboard.php#manage-dogs");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dog</title>
    <!-- Bootstrap and FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body>

<div class="form-container">
    <h2>Add Dog</h2>
    <form action="add_dog.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Dog Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="breed">Breed</label>
            <input type="text" name="breed" id="breed" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="adoption_fee">Adoption Fee</label>
            <input type="number" name="adoption_fee" id="adoption_fee" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="available">Available</option>
                <option value="adopted">Adopted</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control-file" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Dog</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
