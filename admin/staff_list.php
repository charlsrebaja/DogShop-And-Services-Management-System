<?php
include '../config/db.php';

$stmt = $pdo->query("SELECT * FROM staff");
$staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Staff List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staffList as $staff): ?>
            <tr>
                <td><?= htmlspecialchars($staff['id']) ?></td>
                <td><?= htmlspecialchars($staff['staff_name']) ?></td>
                <td><?= htmlspecialchars($staff['position']) ?></td>
                <td><?= htmlspecialchars($staff['contact']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>