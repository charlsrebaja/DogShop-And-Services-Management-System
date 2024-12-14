<?php
include '../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the JSON input from the body of the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if notification ID is provided
if (isset($data['notification_id'])) {
    $notification_id = $data['notification_id'];

    // Check if the notification belongs to the logged-in user
    $checkStmt = $pdo->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ?");
    $checkStmt->execute([$notification_id, $user_id]);
    $notification = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($notification) {
        // Notification found and belongs to the logged-in user, proceed with deletion
        $deleteStmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
        $deleteStmt->execute([$notification_id]);

        echo json_encode(['success' => true, 'message' => 'Notification deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Notification not found or does not belong to you.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Notification ID is required.']);
}
?>
