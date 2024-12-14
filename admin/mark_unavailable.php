<?php
// admin/mark_unavailable.php

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $reason = $_POST['reason'];

    // Insert unavailable slot into the database
    $query = "INSERT INTO unavailable_slots (staff_id, date, start_time, end_time, reason) 
              VALUES (:staff_id, :date, :start_time, :end_time, :reason)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':staff_id' => $staff_id,
        ':date' => $date,
        ':start_time' => $start_time,
        ':end_time' => $end_time,
        ':reason' => $reason,
    ]);

    header("Location: schedule.php?success=1");
    exit;
}

// Fetch all staff for dropdown
$staffQuery = "SELECT id, name FROM staff";
$staffStmt = $pdo->query($staffQuery);
$staff = $staffStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Unavailable Slot</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Mark Unavailable Slot</h1>
        <form method="POST">
            <div class="form-group">
                <label for="staff_id">Staff Member</label>
                <select id="staff_id" name="staff_id" class="form-control" required>
                    <?php foreach ($staff as $member): ?>
                        <option value="<?= $member['id'] ?>"><?= $member['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reason">Reason (Optional)</label>
                <input type="text" id="reason" name="reason" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Mark Unavailable</button>
        </form>
    </div>
</body>
</html>
