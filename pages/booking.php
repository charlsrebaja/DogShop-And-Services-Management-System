<?php
// Example usage:
$staff_id = $_GET['staff_id']; // Retrieved from the previous step
$service_id = $_GET['service_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book a Service</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div id="booking-section">
        <label for="available-dates">Select a Date:</label>
        <select id="available-dates" name="available_date" required></select>

        <label for="available-times">Select a Time:</label>
        <div id="available-times"></div>
    </div>

    <form action="confirm-booking.php" method="POST">
        <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="date" id="selected-date" required>
        <input type="hidden" name="time" id="selected-time" required>
        <button type="submit">Confirm Booking</button>
    </form>

    <script>
        const staffId = <?php echo $staff_id; ?>;
        const serviceId = <?php echo $service_id; ?>;

        // Fetch available slots dynamically
        fetch(`/get-slots.php?staff_id=${staffId}&service_id=${serviceId}`)
            .then(response => response.json())
            .then(slots => {
                const dateSelect = document.getElementById('available-dates');
                const timesDiv = document.getElementById('available-times');

                const dates = [...new Set(slots.map(slot => slot.available_date))];
                dates.forEach(date => {
                    const option = document.createElement('option');
                    option.value = date;
                    option.textContent = date;
                    dateSelect.appendChild(option);
                });

                dateSelect.addEventListener('change', () => {
                    const selectedDate = dateSelect.value;
                    timesDiv.innerHTML = '';

                    const times = slots
                        .filter(slot => slot.available_date === selectedDate)
                        .map(slot => slot.available_time);

                    times.forEach(time => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = time;
                        button.classList.add('time-slot');
                        button.onclick = () => {
                            document.querySelectorAll('.time-slot').forEach(btn => btn.classList.remove('selected'));
                            button.classList.add('selected');
                        };
                        timesDiv.appendChild(button);
                    });
                });

                // Trigger change for first date
                if (dates.length > 0) {
                    dateSelect.value = dates[0];
                    dateSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(error => console.error('Error fetching slots:', error));
    </script>
</body>
</html>
