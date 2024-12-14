<?php
include '../config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

echo "<script>alert('Product deleted successfully'); window.location.href='manage_products.php';</script>";
?>
