
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
</head>
<body>
<div class="container my-5">
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
                    <img src="../uploads/<?php echo htmlspecialchars($staff['image_url']); ?>" alt="Staff Image" style="width: 50px; height: 50px; border-radius: 50%;">
                </td>
                <td><?php echo htmlspecialchars($staff['name']); ?></td>
                <td><?php echo htmlspecialchars($staff['position']); ?></td>
                <td>
                    <a href="edit_staff.php?id=<?php echo $staff['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_staff.php?id=<?php echo $staff['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
