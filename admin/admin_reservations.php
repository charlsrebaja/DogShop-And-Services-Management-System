<?php
// Database connection
include '../config/db.php';

// Fetch all reservations
$sql = "SELECT r.id, r.status, gs.service_name, s.name AS staff_name, u.username 
        FROM reservations r
        JOIN grooming_services gs ON r.service_id = gs.id
        JOIN staff s ON r.staff_id = s.id
        JOIN users u ON r.customer_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Reservations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Reservations</h1>
        <table>
            <tr>
                <th>Service</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['staff_name']; ?></td>
                    <td>
                        <?php echo $row['status']; ?>
                    </td>
                    <td>
                        <form action="update_status.php" method="POST">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <select name="status" required>
                                <option value="Pending" <?php if($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Confirmed" <?php if($row['status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                                <option value="Completed" <?php if($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
