<?php
include '../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $breed = trim($_POST['breed']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $adoption_fee = trim($_POST['adoption_fee']);
    $description = trim($_POST['description']); // Capture description from the form

  // Handle image upload (if any)
$image_url = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Define the path to the dogimg folder
    $image_url = 'dogimg/' . basename($_FILES['image']['name']);

    // Check if the folder exists and create it if not
    if (!file_exists('dogimg')) {
        mkdir('dogimg', 0777, true);
    }

    // Move the uploaded file to the dogimg folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
        echo "File uploaded successfully.";
    } else {
        echo "Failed to upload file.";
    }
}

// Now insert data into the database
$name = trim($_POST['name']);
$breed = trim($_POST['breed']);
$age = trim($_POST['age']);
$gender = trim($_POST['gender']);
$adoption_fee = trim($_POST['adoption_fee']);
$description = trim($_POST['description']); // Capture description from the form

// Insert into the database
try {
    $stmt = $pdo->prepare("INSERT INTO dogs (name, breed, age, gender, adoption_fee, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $breed, $age, $gender, $adoption_fee, $description, $image_url]);

    // Redirect after successful insert
    header('Location: dashboard.php'); // Replace with actual success page
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}
?>


<!-- HTML Form to Insert Data -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Dog</title>
</head>
<body>
    <h2>Insert Dog Information</h2>
    <form action="insert_dog.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Dog Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="breed">Breed</label>
        <input type="text" class="form-control" id="breed" name="breed" required>
    </div>
    <div class="form-group">
        <label for="age">Age</label>
        <input type="number" class="form-control" id="age" name="age" required>
    </div>
    <div class="form-group">
        <label for="gender">Gender</label>
        <select class="form-control" id="gender" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div class="form-group">
        <label for="adoption_fee">Adoption Fee</label>
        <input type="number" class="form-control" id="adoption_fee" name="adoption_fee" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>
    <div class="form-group">
        <label for="image">Dog Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Add Dog</button>
</form>

</body>
</html>
