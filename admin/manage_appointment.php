<?php
include '../config/db.php';

// Fetch services for the filter dropdown
$services_query = "SELECT id, service_name FROM services";
$services_stmt = $pdo->prepare($services_query);
$services_stmt->execute();
$services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle filters
$search = $_GET['search'] ?? '';
$service_filter = $_GET['service_filter'] ?? '';
$status_filter = $_GET['status_filter'] ?? '';

$query = "SELECT a.*, u.username, s.name AS staff_name, serv.service_name 
          FROM appointments a
          JOIN users u ON a.user_id = u.id
          JOIN staff s ON a.staff_id = s.id
          JOIN services serv ON a.service_id = serv.id
          WHERE 1=1"; // Base query

if ($search) {
    $query .= " AND u.username LIKE :search";
}
if ($service_filter) {
    $query .= " AND a.service_id = :service_filter";
}
if ($status_filter) {
    $query .= " AND a.status = :status_filter";
}

$stmt = $pdo->prepare($query);

if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
if ($service_filter) {
    $stmt->bindValue(':service_filter', $service_filter);
}
if ($status_filter) {
    $stmt->bindValue(':status_filter', $status_filter);
}

$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-4">
        <h4 class="fw-bold">Manage Appointments</h4>

        <!-- Filters Section -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by Customer" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="service_filter" class="form-select">
                    <option value="">Filter by Service</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= $service_filter == $service['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['service_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status_filter" class="form-select">
                    <option value="">Filter by Status</option>
                    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $status_filter == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
            </div>
        </form>

        <!-- Appointments Table -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Staff</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['username']) ?></td>
                        <td><?= htmlspecialchars($appointment['service_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['staff_name']) ?></td>
                        <td><?= date("F j, Y", strtotime($appointment['appointment_date'])) ?></td>
                        <td><?= date("h:i A", strtotime($appointment['appointment_time'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $appointment['status'] === 'Completed' ? 'success' : ($appointment['status'] === 'Cancelled' ? 'danger' : 'warning') ?>">
                                <?= htmlspecialchars($appointment['status'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning w-50 mb-1 hover-effect edit-btn"
                                    data-id="<?= $appointment['id'] ?>"
                                    data-customer="<?= htmlspecialchars($appointment['username']) ?>"
                                    data-service="<?= $appointment['service_id'] ?>"
                                    data-staff="<?= $appointment['staff_id'] ?>"
                                    data-date="<?= $appointment['appointment_date'] ?>"
                                    data-time="<?= $appointment['appointment_time'] ?>"
                                    data-status="<?= htmlspecialchars($appointment['status']) ?>">
                                Edit
                            </button>
                            <a href="delete_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-danger w-50 text-dark mb-1 hover-effect"
                               onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Appointment Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="edit_appointment.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="appointmentId">
                        <div class="mb-3">
                            <label for="editCustomer" class="form-label">Customer</label>
                            <input type="text" class="form-control" id="editCustomer" name="customer" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editService" class="form-label">Service</label>
                            <select id="editService" class="form-select" name="service">
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editStaff" class="form-label">Staff</label>
                            <input type="text" class="form-control" id="editStaff" name="staff" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editDate" name="date">
                        </div>
                        <div class="mb-3">
                            <label for="editTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="editTime" name="time">
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select id="editStatus" class="form-select" name="status">
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const customer = this.dataset.customer;
                const service = this.dataset.service;
                const staff = this.dataset.staff;
                const date = this.dataset.date;
                const time = this.dataset.time;
                const status = this.dataset.status;

                document.getElementById('appointmentId').value = id;
                document.getElementById('editCustomer').value = customer;
                document.getElementById('editService').value = service;
                document.getElementById('editStaff').value = staff;
                document.getElementById('editDate').value = date;
                document.getElementById('editTime').value = time;
                document.getElementById('editStatus').value = status;

                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });
    </script>
</body>
</html>
