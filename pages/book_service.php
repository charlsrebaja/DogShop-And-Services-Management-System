
<!--
<?php
include '../config/db.php';

$service_id = $_GET['service_id'];

// Fetch available staff for the selected service
$staff_query = "SELECT staff.id, staff.name FROM staff
                JOIN service_staff ON staff.id = service_staff.staff_id
                WHERE service_staff.service_id = :service_id AND staff.availability = 1";
$stmt = $pdo->prepare($staff_query);
$stmt->execute(['service_id' => $service_id]);
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST" action="process_booking.php">
    <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">

    <label for="staff">Select Staff:</label>
    <select name="staff_id" id="staff" class="form-control">
        <?php foreach ($staff as $person): ?>
            <option value="<?php echo $person['id']; ?>"><?php echo $person['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="date">Preferred Date:</label>
    <input type="date" name="date" id="date" class="form-control" required>

    <button type="submit" class="btn btn-success mt-3">Book Service</button>
</form>
