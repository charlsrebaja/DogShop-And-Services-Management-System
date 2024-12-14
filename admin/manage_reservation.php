<?php
$reservations = $conn->query("SELECT r.id, r.status, r.created_at, s.service_name, st.name AS staff_name, c.name AS customer_name 
                              FROM reservations r
                              JOIN services s ON r.service_id = s.id
                              JOIN staff st ON r.staff_id = st.id
                              JOIN customers c ON r.customer_id = c.id");
?>

<h2>Manage Reservations</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Staff</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($reservation = $reservations->fetch_assoc()) { ?>
            <tr>
                <td><?= $reservation['id'] ?></td>
                <td><?= $reservation['customer_name'] ?></td>
                <td><?= $reservation['service_name'] ?></td>
                <td><?= $reservation['staff_name'] ?></td>
                <td><?= $reservation['status'] ?></td>
                <td>
                    <form action="update_reservation.php" method="POST">
                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                        <select name="status">
                            <option value="Pending" <?= $reservation['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Confirmed" <?= $reservation['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="Completed" <?= $reservation['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
