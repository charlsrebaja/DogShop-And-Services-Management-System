<?php
include "../config/db.php";
$id = $_GET['id'];

// Delete staff
$query = "DELETE FROM staff WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

header("Location: manage_staff.php");
?>
