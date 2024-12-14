<?php
include '../config/db.php'; // Include database connection

// Fetch all staff members
$query = "SELECT * FROM staff";
$stmt = $pdo->query($query);
$staff_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Manage Staff</h2>
    <a href="add_staff.php" class="btn btn-dark mb-3">Add Staff</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff_members as $staff): ?>
                <tr>
    <td><?php echo htmlspecialchars($staff['id']); ?></td>
    <td>
        <?php if (!empty($staff['image_url'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($staff['image_url']); ?>" alt="Staff Image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
        <?php else: ?>
            No image
        <?php endif; ?>
    </td>
    <td><?php echo htmlspecialchars($staff['name']); ?></td>
    <td><?php echo htmlspecialchars($staff['position']); ?></td>
    <td>
        <!-- Edit Button -->
        <button class="btn btn-warning btn-sm  mb-1 hover-effect" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $staff['id']; ?>">Edit</button>
        
        <!-- Delete Button -->
        <a href="delete_staff.php?id=<?php echo $staff['id']; ?>" class="btn btn-danger btn-sm text-dark mb-1 hover-effect" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
    </td>
</tr>


           <!-- Edit Modal -->
<div class="modal fade" id="editModal<?php echo $staff['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="edit_staff.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($staff['id']); ?>">

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
                    </div>

                    <!-- Position Field -->
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($staff['position']); ?>" required>
                    </div>

                    <!-- Current Image Preview -->
                    <div class="form-group">
                        <label for="currentImage">Current Image</label>
                        <div>
                            <?php if (!empty($staff['image_url'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($staff['image_url']); ?>" alt="Staff Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10%;">
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Image Upload Field -->
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Edit Modal -->


            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
