<?php 
include '../config/db.php';

// Check if service ID is provided in the URL
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Fetch the service to get the image URL
    $query = "SELECT image_url FROM services WHERE id = :service_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':service_id' => $service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the service doesn't exist, redirect to the service list
    if (!$service) {
        header('Location: services_list.php');
        exit;
    }

    // Delete the service from the database
    $delete_query = "DELETE FROM services WHERE id = :service_id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([':service_id' => $service_id]);

    // Optionally delete the image file if it exists
    if ($service['image_url']) {
        $image_path = "../uploads/" . $service['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image
        }
    }

    // Redirect to services list page with a success message
    header('Location: services_list.php?message=Service deleted successfully');
    exit;
} else {
    // If no service ID is provided, redirect to the services list
    header('Location: services_list.php');
    exit;
}
?>
