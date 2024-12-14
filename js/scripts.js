document.addEventListener("DOMContentLoaded", function() {
    fetch("../config/get_services_staff.php")
        .then(response => response.json())
        .then(data => {
            const serviceSelect = document.getElementById("service_name");
            const staffSelect = document.getElementById("staff_id");

            data.services.forEach(service => {
                const option = document.createElement("option");
                option.value = service.service_name;
                option.textContent = service.service_name;
                serviceSelect.appendChild(option);
            });

            data.staff.forEach(staff => {
                const option = document.createElement("option");
                option.value = staff.id;
                option.textContent = staff.staff_name;
                staffSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching services and staff:", error));
});