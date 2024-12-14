<?php
include '../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch unread notifications (is_read = 0)
$notificationStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$notificationStmt->execute([$user_id]);
$unreadNotifications = $notificationStmt->fetchColumn();

// Fetch all notifications for the user
$notificationsStmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$notificationsStmt->execute([$user_id]);
$notifications = $notificationsStmt->fetchAll();

// Mark notifications as read on view
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];
    $markReadStmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $markReadStmt->execute([$notification_id]);
    echo json_encode(['success' => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- For High-Resolution Displays -->
     <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
        }
        .notification-header {
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification-card {
            background-color: white;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .notification-card.unread {
            border-left: 4px solid #007bff;
            background-color: #f1f8ff;
        }
        .notification-card.read {
            background-color: #e9ecef;
        }
        .notification-icon {
            font-size: 24px;
            color: #007bff;
            margin-right: 15px;
        }
        .created-at {
            font-size: 0.85em;
            color: #6c757d;
        }
        .notification-card:hover {
            background-color: #f1f1f1;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .delete-btn {
            cursor: pointer;
            color: red;
            font-size: 18px;
            margin-left: auto;
        }
        .no-notifications {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="notification-header">
        <h4>Notifications</h4>
        <span class="badge bg-light text-dark">Unread: <?php echo $unreadNotifications; ?></span>
    </div>

    <?php if (empty($notifications)): ?>
        <div class="no-notifications">
            <img src="https://via.placeholder.com/150" alt="No Notifications" class="mb-3">
            <p class="text-muted">You have no notifications at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification-card d-flex align-items-center <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>" 
                 id="notification-<?php echo $notification['id']; ?>" 
                 onclick="markAsRead(<?php echo $notification['id']; ?>)">
                <i class="notification-icon fas fa-bell"></i>
                <div>
                    <h5 class="mb-1"><?php echo htmlspecialchars($notification['message']); ?></h5>
                    <p class="created-at mb-0"><?php echo htmlspecialchars($notification['created_at']); ?></p>
                </div>
                <span class="delete-btn" onclick="deleteNotification(<?php echo $notification['id']; ?>)">🗑️</span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    function markAsRead(notification_id) {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notification_id: notification_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById('notification-' + notification_id);
                notification.classList.remove('unread');
                notification.classList.add('read');
            }
        });
    }

    function deleteNotification(notification_id) {
        event.stopPropagation();
        fetch('delete_notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notification_id: notification_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById('notification-' + notification_id);
                notification.remove();
            } else {
                alert('Failed to delete notification: ' + data.message);
            }
        });
    }

    
</script>
</body>
</html>
