<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grooming Services</title>
    <link rel="stylesheet" href="styles.css">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Book a Grooming Service</h1>
        
        <!-- FullCalendar display -->
        <div id="calendar"></div>

        <div id="booking-form" style="display:none;">
            <h2>Book Appointment</h2>
            <form action="book_service.php" method="POST">
                <input type="hidden" name="service_id" value="<?php echo $_GET['service_id']; ?>">
                <input type="hidden" name="selected_date" id="selected_date">
                <input type="hidden" name="selected_time" id="selected_time">

                <label for="staff">Select Staff:</label>
                <select name="staff_id" id="staff" required>
                    <?php
                    $staff_result = $conn->query("SELECT id, name FROM staff");
                    while($staff = $staff_result->fetch_assoc()) {
                        echo "<option value='".$staff['id']."'>".$staff['name']."</option>";
                    }
                    ?>
                </select><br><br>

                <label for="time">Select Time:</label>
                <input type="time" id="time" name="time" required><br><br>

                <button type="submit">Book Now</button>
            </form>
        </div>
    </div>

    <script>
        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            events: 'fetch_slots.php', // Fetch the available slots from the database
            dateClick: function(info) {
                // Show booking form when a date is clicked
                document.getElementById('booking-form').style.display = 'block';
                document.getElementById('selected_date').value = info.dateStr; // Set selected date
            }
        });
        calendar.render();
    </script>
</body>
</html>
