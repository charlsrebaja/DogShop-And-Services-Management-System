<?php
include '../config/db.php';

$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services List</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Services List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= htmlspecialchars($service['id']) ?></td>
                <td><?= htmlspecialchars($service['service_name']) ?></td>
                <td><?=htmlspecialchars($service['description']) ?></td>
                <td><?= htmlspecialchars($service['price']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

