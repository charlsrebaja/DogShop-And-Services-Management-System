<?php
include '../config/db.php';

// Fetch the dog details based on the ID passed in the URL
$dogId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ?");
$stmt->execute([$dogId]);
$dog = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the dog exists
if ($dog) {
    $dogName = htmlspecialchars($dog['name']);
    $dogBreed = htmlspecialchars($dog['breed']);
    $adoptionFee = htmlspecialchars($dog['adoption_fee']);
    $currentImage = htmlspecialchars($dog['image_url']);
    $dogStatus = htmlspecialchars($dog['status']);
    
} else {
    // If no dog is found with the provided ID
    echo "<p class='text-danger'>Dog not found!</p>";
    exit;
}
?>

<!-- Edit Dog Form -->
<form action="update_dog.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $dogId; ?>">
    
    <div class="mb-3">
        <label for="dogName" class="form-label">Dog Name</label>
        <input type="text" class="form-control" id="dogName" name="name" value="<?php echo $dogName; ?>" required>
    </div>

    <div class="mb-3">
        <label for="dogBreed" class="form-label">Breed</label>
        <input type="text" class="form-control" id="dogBreed" name="breed" value="<?php echo $dogBreed; ?>" required>
    </div>

    <div class="mb-3">
        <label for="adoptionFee" class="form-label">Adoption Fee</label>
        <input type="number" class="form-control" id="adoptionFee" name="adoption_fee" value="<?php echo $adoptionFee; ?>" required>
    </div>

    <div class="mb-3">
        <label for="dogImage" class="form-label">Image</label>
        <input type="file" class="form-control" id="dogImage" name="image" accept="image/*">
        <?php if ($currentImage): ?>
            <div class="mt-2">
                <p><strong>Current Image:</strong></p>
                <img src="../dogimg/<?php echo $currentImage; ?>" alt="Current Dog Image" style="width: 100px; height: 100px; border-radius: 50%;">
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="dogStatus" class="form-label">Status</label>
        <select class="form-control" id="dogStatus" name="status" required>
            <option value="Available" <?php echo $dogStatus == 'Available' ? 'selected' : ''; ?>>Available</option>
            <option value="Adopted" <?php echo $dogStatus == 'Adopted' ? 'selected' : ''; ?>>Adopted</option>
        </select>
    </div>

    <button type="submit" class="btn btn-dark">Update Dog</button>
</form>
