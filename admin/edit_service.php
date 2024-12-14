<?php
include '../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $service_id = $_POST['service_id'] ?? null;
    $service_name = $_POST['service_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;

    // Validate inputs
    if (!$service_id || !$service_name || !$description || !$price || !is_numeric($price)) {
        die("Invalid input. Please go back and try again.");
    }

    try {
        // Prepare the SQL update query
        $query = "UPDATE services SET service_name = :service_name, description = :description, price = :price WHERE id = :service_id";
        $stmt = $pdo->prepare($query);

        // Bind values
        $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the manage services page with success message
            header("Location: manage_services.php?success=Service updated successfully.");
            exit;
        } else {
            throw new Exception("Failed to update the service. Please try again.");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // If the request method is not POST, redirect back
    header("Location: manage_services.php");
    exit;
}
