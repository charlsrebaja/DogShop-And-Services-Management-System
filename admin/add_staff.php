<?php
include "../config/db.php";

if (isset($_POST['submit'])) {
    // Collect form data
    $name = $_POST['name'];
    $position = $_POST['position'];
    $bio = $_POST['bio'];

    // Handle file upload
    $target_dir = "../uploads/"; // Directory where the image will be uploaded
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;

    // Check if the uploaded file is an image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (isset($_FILES["image"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size (optional, limit to 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific file types
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Attempt to upload the file if everything is okay
    if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert staff into the database
        $query = "INSERT INTO staff (name, position, bio, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $position, $bio, $image_name]);

        // Redirect to the manage staff page
        header("Location: manage_staff.php");
        exit(); // Make sure no further code is executed
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2>Add Staff</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
            <small class="form-text text-muted">Accepted formats: JPG, JPEG, PNG, GIF. Max size: 2MB.</small>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Add Staff</button>
    </form>
</div>
</body>
</html>
