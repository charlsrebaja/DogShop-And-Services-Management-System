<?php
include '../config/db.php';

// Fetch all dogs
$dogs_stmt = $pdo->query("SELECT * FROM dogs ORDER BY created_at DESC");
$dogs = $dogs_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="">
   <!-- Add Dog Button (opens the Add Dog modal) -->
    <h2>Manage Dog</h2>
   <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#addDogModal">Add New Dog</button>

   <table class="table table-striped">
       <thead>
           <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Breed</th>
               <th>Adoption Fee</th>
               <th>Image</th>
               <th>Status</th>
               <th>Actions</th>
           </tr>
       </thead>
       <tbody>
           <?php foreach ($dogs as $dog): ?>
            <tr>
                <td><?php echo $dog['id']; ?></td>
                <td><?php echo htmlspecialchars($dog['name']); ?></td>
                <td><?php echo htmlspecialchars($dog['breed']); ?></td>
                <td>$<?php echo htmlspecialchars($dog['adoption_fee']); ?></td>
                <td>
                    <img src="../dogimg/<?php echo htmlspecialchars($dog['image_url']); ?>" alt="Dog Image" style="width: 50px; height: 50px; border-radius: 50%;">
                </td>
                <td><?php echo htmlspecialchars($dog['status']); ?></td>
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning btn-sm w-50 mb-1 hover-effect" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editDogModal" 
                            data-id="<?php echo $dog['id']; ?>">Edit</button>
                    <!-- Delete Button -->
                    <a href="delete_dog.php?id=<?php echo $dog['id']; ?>" 
                    class="btn btn-danger btn-sm w-50 text-dark mb-1 hover-effect" 
                    onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>

           <?php endforeach; ?>
       </tbody>
   </table>
</div>

<!-- Add New Dog Modal -->
<div class="modal fade" id="addDogModal" tabindex="-1" aria-labelledby="addDogModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDogModalLabel">Add New Dog</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 20px;">
                <!-- The form for adding a new dog -->
                <form action="add_dog.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="dogName" class="form-label">Dog Name</label>
                        <input type="text" class="form-control" id="dogName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dogBreed" class="form-label">Breed</label>
                        <input type="text" class="form-control" id="dogBreed" name="breed" required>
                    </div>
                    <div class="mb-3">
                        <label for="adoptionFee" class="form-label">Adoption Fee</label>
                        <input type="number" class="form-control" id="adoptionFee" name="adoption_fee" required>
                    </div>
                    <div class="mb-3">
                        <label for="dogImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="dogImage" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="dogStatus" class="form-label">Status</label>
                        <select class="form-control" id="dogStatus" name="status" required>
                            <option value="Available">Available</option>
                            <option value="Adopted">Adopted</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-dark">Add Dog</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDogModal" tabindex="-1" aria-labelledby="editDogModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDogModalLabel">Edit Dog Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 20px;">
                <!-- The form will be dynamically loaded via AJAX -->
                <div id="modal-content-placeholder"></div>
            </div>
        </div>
    </div>
</div>


<!-- Include Bootstrap JS and your custom animation CSS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Animation CSS -->
<style>
    /* Adding smooth fade-in and scaling effect */
    .modal.fade .modal-dialog {
        transform: scale(0.7); /* Start with a smaller scale */
        opacity: 0; /* Initial state is hidden */
        transition: transform 0.3s ease, opacity 0.3s ease; /* Smooth transition for scale and opacity */
    }

    /* When the modal is shown, it will scale up and fade in */
    .modal.show .modal-dialog {
        transform: scale(1); /* Modal will be at normal size */
        opacity: 1; /* Modal becomes fully visible */
    }
    .modal-header{
        background: #158691 !important;
        color: white;
    }
</style>

<!-- Script to dynamically load content into the modal -->
<script>
    // Load content into the modal dynamically when the Edit button is clicked
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editDogModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const dogId = button.getAttribute('data-id'); // Extract the dog's ID
            const modalContent = document.getElementById('modal-content-placeholder');
            
            // Make an AJAX call to fetch the edit_dog.php content
            fetch(`edit_dog.php?id=${dogId}`)
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data;
                })
                .catch(error => {
                    modalContent.innerHTML = `<p class="text-danger">Error loading content. Please try again later.</p>`;
                });
        });
    });
</script>
