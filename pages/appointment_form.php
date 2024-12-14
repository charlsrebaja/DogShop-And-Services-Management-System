<?php
session_start(); // Start the session to access user data

include '../config/db.php'; // Include database connection

// Initialize variables to avoid undefined errors when displaying errors
$services = [];
$staff = [];
$errorMessage = '';

// Fetch services from the database
$services_query = "SELECT id, service_name FROM services";
$services_stmt = $pdo->query($services_query);
$services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $userId = $_SESSION['user_id']; // Logged in user ID
    $service = $_POST['service_name'];
    $staffId = $_POST['staff'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    // Insert the appointment into the database
    $sql = "INSERT INTO appointments (user_id, staff_id, service_id, appointment_date, appointment_time) 
            VALUES (:user_id, :staff_id, :service_id, :appointment_date, :appointment_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':staff_id' => $staffId,
        ':service_id' => $service,  // Use the selected service ID
        ':appointment_date' => $appointmentDate,
        ':appointment_time' => $appointmentTime,
    ]);

    // Set success flag for JavaScript
    $success = true;
}

// Fetch staff members for dropdown
$stmt = $pdo->query("SELECT * FROM staff");
$staffMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <!-- For High-Resolution Displays -->
    <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>
        body {
        font-family: Arial, sans-serif;
        background-image: url(../uploads/appointmentbg.jpg);
        background-repeat: no-repeat;
        background-size: cover; /* Ensures the image covers the entire background */
        background-attachment: fixed; /* Keeps the background fixed while scrolling */
        color: #333;
    }

    .container {
        max-width: 800px;
        padding: 15px;
        margin: 0 auto;
    }

    #calendar {
        margin: auto;
        max-width: 70%;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Weekdays and Days Styling */
    .fc-day-header {
        background-color: #04BACC;
        color: white;
        font-weight: bold;
        text-align: center;
        font-size: 1.1rem;
    }

    .fc-day-number {
        background-color: #f1f1f1;
        color: #333;
        border-radius: 50%;
        padding: 5px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .fc-day-number:hover {
        background-color: #04BACC;
        color: white;
        cursor: pointer;
    }

    .fc-day-sun .fc-day-number {
        color: #d9534f; /* Sundays in red */
    }

    /* Highlighting selected day */
    .fc-day-number.fc-state-highlight {
        background-color: #ff8c00;
        color: white;
        border-radius: 50%;
    }

    /* Calendar navigation buttons */
    .fc-prev-button, .fc-next-button {
        background-color: #04BACC;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 1.1rem;
        transition: background-color 0.3s;
    }

    .fc-prev-button:hover, .fc-next-button:hover {
        background-color: #0288a7;
    }

    /* Modal header */
    .modal-header {
        background-color: #04BACC;
        color: white;
        border-bottom: 2px solid #0288a7;
        padding: 15px;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Modal body */
    .modal-body {
        padding: 20px;
        font-size: 1.1rem;
    }

    /* Footer */
    footer {
        background: #158691 !important;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 50px;
        font-size: 0.9rem;
    }

    footer a {
        color: #dce7f7;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }

</style>

</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container my-5">
    <div id="calendar"></div>
</div>

<!-- Modal for Booking Appointment -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Book Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="appointmentForm" method="POST">
                <div class="modal-body">
                    <!-- Appointment Details -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="appointmentDate" class="form-label">Date</label>
                            <input type="hidden" id="appointmentDate" name="appointment_date" required>
                            <input type="date" class="form-control" id="appointmentDateDisplay" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="appointmentTime" class="form-label">Time</label>
                            <input type="time" id="appointmentTime" name="appointment_time" class="form-control" required>
                        </div>
                    </div>
                    <!-- Owner Details -->
                    <h6 class="mt-4 text-primary">Owner Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ownerName" class="form-label">Name</label>
                            <input type="text" id="ownerName" name="owner_name" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ownerContact" class="form-label">Contact</label>
                            <input type="text" id="ownerContact" name="owner_contact" class="form-control" placeholder="Enter contact number" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="ownerEmail" class="form-label">Email</label>
                            <input type="email" id="ownerEmail" name="owner_email" class="form-control" placeholder="Enter email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ownerAddress" class="form-label">Address</label>
                            <textarea id="ownerAddress" name="owner_address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                        </div>
                    </div>

                    <!-- Dog Information -->
                    <h6 class="mt-4 text-primary">Dog Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="dogName" class="form-label">Name</label>
                            <input type="text" id="dogName" name="dog_name" class="form-control" placeholder="Enter dog's name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="dogBreed" class="form-label">Breed</label>
                            <input type="text" id="dogBreed" name="dog_breed" class="form-control" placeholder="Enter dog's breed" required>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="dogAge" class="form-label">Age</label>
                        <input type="text" id="dogAge" name="dog_age" class="form-control" placeholder="Enter dog's age" required>
                    </div>

                    <!-- Service Selection -->
                    <div class="mb-3">
                        <label for="service" class="form-label">Service</label>
                        <select id="service" name="service_name" class="form-select" required>
                            <option value="">Select a Service</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['id']); ?>">
                                    <?php echo htmlspecialchars($service['service_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Please select a service.</div>
                    </div>

                    <!-- Staff Selection -->
                    <div>
                        <label for="staff" class="form-label">Staff</label>
                        <select id="staff" name="staff" class="form-select" required>
                            <option value="">Select a Staff</option>
                            <?php foreach ($staffMembers as $staff): ?>
                                <option value="<?= $staff['id'] ?>"><?= htmlspecialchars($staff['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    // Initialize calendar
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'en',
        dateClick: function(info) {
            var date = info.dateStr;
            document.getElementById('appointmentDate').value = date;
            document.getElementById('appointmentDateDisplay').value = date;
            var appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
            appointmentModal.show();
        }
    });
    calendar.render();

    <?php if (isset($success) && $success): ?>
        alert("Appointment booked successfully!");
    <?php endif; ?>
</script>


<?php include 'footer.php'; ?>

</body>
</html>
