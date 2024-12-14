<?php
include '../config/db.php';

// Fetch all unavailable slots
$query = "
    SELECT u.id, st.name AS staff_name, u.date, u.start_time, u.end_time, u.reason
    FROM unavailable_slots u
    JOIN staff st ON u.staff_id = st.id
    ORDER BY u.date, u.start_time";
$stmt = $pdo->query($query);
$unavailableSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Schedules</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Staff Schedules</h1>
        <a href="mark_unavailable.php" class="btn btn-success mb-3">Mark Unavailable Slot</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unavailableSlots as $slot): ?>
                <tr>
                    <td><?= $slot['id'] ?></td>
                    <td><?= $slot['staff_name'] ?></td>
                    <td><?= $slot['date'] ?></td>
                    <td><?= $slot['start_time'] ?></td>
                    <td><?= $slot['end_time'] ?></td>
                    <td><?= $slot['reason'] ?></td>
                    <td>
                        <a href="delete_slot.php?id=<?= $slot['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
