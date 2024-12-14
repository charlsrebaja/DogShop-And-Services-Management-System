<?php
include '../config/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    exit;
}

// Fetch all adoption requests
$stmt = $pdo->prepare("SELECT ar.id, ar.reason, ar.preferred_contact, ar.adoption_date, ar.status, 
                               ar.email, ar.address, ar.contact_number, ar.valid_id,
                               d.name AS dog_name, d.breed, d.adoption_fee, 
                               u.username AS customer_name, d.id AS dog_id
                                FROM adoptions ar
                                JOIN dogs d ON ar.dog_id = d.id
                                JOIN users u ON ar.customer_id = u.id
                                ORDER BY ar.adoption_date DESC");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating status or deleting request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) {
        // Update status for approval or rejection
        $request_id = $_POST['request_id'];
        $new_status = $_POST['status'];
        $dog_id = $_POST['dog_id'];

        // Update the adoption request status
        $updateStmt = $pdo->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
        $updateStmt->execute([$new_status, $request_id]);

        // Fetch customer ID for the request
        $customerStmt = $pdo->prepare("SELECT customer_id FROM adoptions WHERE id = ?");
        $customerStmt->execute([$request_id]);
        $customer_id = $customerStmt->fetchColumn();

        // Create a notification message
        $dogNameStmt = $pdo->prepare("SELECT name FROM dogs WHERE id = ?");
        $dogNameStmt->execute([$dog_id]);
        $dog_name = $dogNameStmt->fetchColumn();

        $message = "Your adoption request for '$dog_name' has been $new_status.";

        // Insert notification
        $notificationStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $notificationStmt->execute([$customer_id, $message]);

        // Mark dog as unavailable if approved
        if ($new_status === 'Approved') {
            $dogUpdateStmt = $pdo->prepare("UPDATE dogs SET is_available = 0 WHERE id = ?");
            $dogUpdateStmt->execute([$dog_id]);
        }

        echo "<script>alert('Adoption request updated successfully!'); window.location.href='dashboard.php';</script>";
        exit;
    } elseif (isset($_POST['delete_request'])) {
        // Delete adoption request
        $request_id = $_POST['request_id'];
        $dog_id = $_POST['dog_id'];

        // Delete the adoption request from the database
        $deleteStmt = $pdo->prepare("DELETE FROM adoptions WHERE id = ?");
        $deleteStmt->execute([$request_id]);

        // Make the dog available again
        $dogUpdateStmt = $pdo->prepare("UPDATE dogs SET is_available = 1 WHERE id = ?");
        $dogUpdateStmt->execute([$dog_id]);

        echo "<script>alert('Adoption request deleted successfully!'); window.location.href='dashboard.php#manage_adoption_request';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Requests Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h2>Manage Adoption</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Dog</th>
                <th>Email</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Adoption Fee</th>
                <th>Date</th>
                <th>Valid ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
            <tr>
                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($request['dog_name']); ?> (<?php echo htmlspecialchars($request['breed']); ?>)</td>
                <td><?php echo htmlspecialchars($request['email']); ?></td>
                <td><?php echo htmlspecialchars($request['address']); ?></td>
                <td><?php echo htmlspecialchars($request['contact_number']); ?></td>
                <td>$<?php echo htmlspecialchars($request['adoption_fee']); ?></td>
                <td><?php echo htmlspecialchars($request['adoption_date']); ?></td>
                <td>
                    <?php if ($request['valid_id']): ?>
                        <a href="../uploads/ids/<?php echo htmlspecialchars($request['valid_id']); ?>" target="_blank" style="color: blue; text-decoration: none;">View</a>
                    <?php else: ?>
                        No ID uploaded
                    <?php endif; ?>
                </td>

                <td><?php echo htmlspecialchars($request['status']); ?></td>
                <td>
                                    <!-- Approve Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="dog_id" value="<?php echo $request['dog_id']; ?>">
                        <button type="submit" name="status" value="Approved" class="btn btn-dark btn-sm">Approve</button>
                    </form>

                    <!-- Reject Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="dog_id" value="<?php echo $request['dog_id']; ?>">
                        <button type="submit" name="status" value="Rejected" class="btn btn-warning btn-sm">Reject</button>
                    </form>

                    <!-- Delete Request Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="dog_id" value="<?php echo $request['dog_id']; ?>">
                        <button type="submit" name="delete_request" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
