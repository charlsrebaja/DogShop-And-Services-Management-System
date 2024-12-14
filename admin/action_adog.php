
 <!--This script will handle the actions to approve or reject adoption requests.-->
<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized access!";
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $adoption_id = $_GET['id'];
    $action = $_GET['action'];

    // Update adoption status
    if ($action === 'approve') {
        $status = 'Approved';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } else {
        echo "Invalid action!";
        exit;
    }

    $stmt = $pdo->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $adoption_id])) {
        echo "<script>alert('Adoption request $status successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "Failed to update adoption status!";
    }
} else {
    echo "Missing parameters!";
}
?>

