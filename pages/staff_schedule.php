<?php
include '../config/db.php';

// Handle schedule updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    $sql = "INSERT INTO staff_schedule (staff_id, day_of_week, start_time, end_time, is_available)
            VALUES ('$staff_id', '$day_of_week', '$start_time', '$end_time', '$is_available')
            ON DUPLICATE KEY UPDATE 
                start_time = '$start_time', 
                end_time = '$end_time', 
                is_available = '$is_available'";

    if ($conn->query($sql) === TRUE) {
        echo "Schedule updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch staff schedules
$schedules = $conn->query("SELECT ss.*, s.name AS staff_name 
                           FROM staff_schedule ss 
                           JOIN staff s ON ss.staff_id = s.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff Schedule</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Staff Schedule</h1>

    <form action="" method="POST">
        <label for="staff_id">Staff:</label>
        <select name="staff_id" id="staff_id" required>
            <?php
            $staff_result = $conn->query("SELECT id, name FROM staff");
            while ($staff = $staff_result->fetch_assoc()) {
                echo "<option value='" . $staff['id'] . "'>" . $staff['name'] . "</option>";
            }
            ?>
        </select><br>

        <label for="day_of_week">Day of Week:</label>
        <select name="day_of_week" id="day_of_week" required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select><br>

        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" required><br>

        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" required><br>

        <label for="is_available">Is Available:</label>
        <input type="checkbox" name="is_available" id="is_available" checked><br>

        <button type="submit">Save Schedule</button>
    </form>

    <h2>Existing Schedules</h2>
    <table>
        <thead>
            <tr>
                <th>Staff</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($schedule = $schedules->fetch_assoc()) { ?>
                <tr>
                    <td><?= $schedule['staff_name'] ?></td>
                    <td><?= $schedule['day_of_week'] ?></td>
                    <td><?= $schedule['start_time'] ?></td>
                    <td><?= $schedule['end_time'] ?></td>
                    <td><?= $schedule['is_available'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
