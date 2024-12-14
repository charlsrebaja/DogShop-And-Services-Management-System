<?php
include '../config/db.php'; // Include your database connection

// Display success or error messages if set in the URL
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
} elseif (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        /* Hover effect for buttons */
            .hover-effect:hover {
                transform: scale(1.02);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .hover-effect {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

    </style>
</head>
<body>
<div class="container">
    <h2>Manage Services</h2>
    <a href="add_service.php" class="btn btn-dark mb-3">Add New Service</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Staff Assigned</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT s.id AS service_id, s.service_name, s.description, s.price, 
                    st.name AS staff_name, s.image_url
                FROM services s
                LEFT JOIN service_staff ss ON s.id = ss.service_id
                LEFT JOIN staff st ON ss.staff_id = st.id
            ";
            $stmt = $pdo->query($query);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['service_id']); ?></td>
                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($service['price']); ?></td>
                    <td><?php echo htmlspecialchars($service['staff_name'] ?: 'None'); ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm w-100 mb-1 hover-effect" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $service['service_id']; ?>">Edit</button>
                        <!-- Assign Staff Button -->
                        <button class="btn btn-dark text-white btn-sm w-100 mb-1 hover-effect" data-bs-toggle="modal" data-bs-target="#assignModal<?php echo $service['service_id']; ?>">Assign</button>
                        <!-- Delete Button -->
                        <a href="delete_service.php?id=<?php echo $service['service_id']; ?>" class="btn btn-danger btn-sm w-100 text-dark hover-effect" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>



                <!-- Edit Modal -->
<div class="modal fade" id="editModal<?php echo $service['service_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $service['service_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="edit_service.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?php echo $service['service_id']; ?>">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['service_id']); ?>">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($service['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($service['price']); ?>" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Service Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <img src="../uploads/<?php echo htmlspecialchars($service['image_url']); ?>" alt="Service Image" width="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

                <!-- Assign Staff Modal (Already Defined) -->
                <div class="modal fade" id="assignModal<?php echo $service['service_id']; ?>" tabindex="-1" aria-labelledby="assignModalLabel<?php echo $service['service_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="assign_staff.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="assignModalLabel<?php echo $service['service_id']; ?>">Assign Staff to Service</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['service_id']); ?>">
                                    <div class="mb-3">
                                        <label for="staff_id" class="form-label">Select Staff</label>
                                        <select class="form-select" id="staff_id" name="staff_id" required>
                                            <?php
                                            // Fetch all staff members for the dropdown
                                            $staff_query = "SELECT id, name FROM staff";
                                            $staff_stmt = $pdo->query($staff_query);
                                            $staff_members = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Display staff members in the dropdown
                                            foreach ($staff_members as $staff):
                                            ?>
                                                <option value="<?php echo $staff['id']; ?>"><?php echo htmlspecialchars($staff['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Assign</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Assign Staff Modal -->

            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
