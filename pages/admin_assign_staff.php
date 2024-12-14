<?php
include '../config/db.php';

// Fetch services
$services_query = "SELECT * FROM services";
$services_stmt = $pdo->query($services_query);
$services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch staff
$staff_query = "SELECT * FROM staff";
$staff_stmt = $pdo->query($staff_query);
$staff = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST" action="assign_staff.php">
    <label for="service">Select Service:</label>
    <select name="service_id" id="service">
        <?php foreach ($services as $service): ?>
            <option value="<?php echo $service['id']; ?>"><?php echo $service['service_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="staff">Select Staff:</label>
    <select name="staff_id" id="staff">
        <?php foreach ($staff as $person): ?>
            <option value="<?php echo $person['id']; ?>"><?php echo $person['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary">Assign</button>
</form>
