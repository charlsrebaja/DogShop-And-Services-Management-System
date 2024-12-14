<?php
include '../config/db.php';

// Fetch all users
$stmt = $pdo->query("SELECT id, username, email, role, image_url FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Assign the result to the $users variable
?>


<!-- Manage Users Section -->
<div id="manage-users">
<h2>Manage Users</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <img src="../uploads/<?php echo htmlspecialchars($user['image_url']); ?>" 
                             alt="User Image" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm edit-user-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal" 
                                data-id="<?php echo $user['id']; ?>">
                            Edit
                        </button>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this user?')">
                           Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editUserContent">
                <!-- Form content will be dynamically loaded here -->
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const editButtons = document.querySelectorAll(".edit-user-btn");

        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                const userId = this.getAttribute("data-id");
                const modalContent = document.getElementById("editUserContent");

                // Clear the previous content
                modalContent.innerHTML = "<p>Loading...</p>";

                // Fetch the form via AJAX
                fetch(`edit_user.php?id=${userId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.text();
                    })
                    .then(html => {
                        modalContent.innerHTML = html;
                    })
                    .catch(error => {
                        modalContent.innerHTML = `<p class="text-danger">Failed to load form: ${error.message}</p>`;
                    });

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById("editUserModal"));
                modal.show();
            });
        });
    });
</script>
