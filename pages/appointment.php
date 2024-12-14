<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Book Grooming Appointment</h1>
    <form id="appointmentForm" method="POST" action="../config/save_appointment.php">
        <input type="hidden" name="user_id" value="1"> <!-- Replace with dynamic user ID -->

        <label for="service_name">Service:</label>
        <select name="service_name" id="service_name" required></select>

        <label for="staff_id">Staff:</label>
        <select name="staff_id" id="staff_id" required></select>

        <label for="appointment_date">Date:</label>
        <input type="date" name="appointment_date" id="appointment_date" required>

        <label for="appointment_time">Time:</label>
        <input type="time" name="appointment_time" id="appointment_time" required>

        <button type="submit">Book Now</button>
    </form>
    <script src="../js/scripts.js"></script>
</body>
</html>