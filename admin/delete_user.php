<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

header("Location: dashboard.php#manage-users");
exit;
?>
