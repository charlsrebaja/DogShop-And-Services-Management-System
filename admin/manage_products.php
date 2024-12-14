<?php
include '../config/db.php'; // Include database connection

// Check if a category filter is set
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products, filtered by category if set
$query = "SELECT * FROM products";
if ($category_filter) {
    $query .= " WHERE category = :category ORDER BY id DESC";
} else {
    $query .= " ORDER BY id DESC";
}

$stmt = $pdo->prepare($query);

if ($category_filter) {
    $stmt->bindParam(':category', $category_filter);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch unique categories for the filter dropdown
$categoryQuery = "SELECT DISTINCT category FROM products";
$categoryStmt = $pdo->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Full jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .product-image {
            max-width: 60px;
            height: auto;
            border-radius: 8px;
        }
        .modal-body img {
            max-width: 120px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Manage Products</h2>

    <!-- Category Filter Form -->
    <form method="GET" class="mb-3">
        <div class="form-row align-items-center">
            <div class="col-auto">
                <label for="categoryFilter" class="sr-only">Category</label>
                <select class="form-control" id="categoryFilter" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['category']); ?>" 
                                <?php echo ($category['category'] == $category_filter) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark">Search</button>
            </div>
        </div>
    </form>

    <button type="button" class="btn btn-dark mb-3" data-toggle="modal" data-target="#addProductModal">Add Product</button>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo $product['stock']; ?></td>
                <td>
                    <?php if ($product['image_url']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                    <?php else: ?>
                        <span>No image</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm w-100 mb-1 hover-effect" data-toggle="modal" data-target="#editProductModal" 
                        data-id="<?php echo $product['id']; ?>"
                        data-product_name="<?php echo htmlspecialchars($product['product_name']); ?>"
                        data-description="<?php echo htmlspecialchars($product['description']); ?>"
                        data-price="<?php echo $product['price']; ?>"
                        data-stock="<?php echo $product['stock']; ?>"
                        data-category="<?php echo htmlspecialchars($product['category']); ?>"
                        data-image_url="<?php echo htmlspecialchars($product['image_url']); ?>"
                    >
                        Edit
                    </button>
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm w-100 mt-2 mb-1 hover-effect text-dark" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>




<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_product.php" method="POST" enctype="multipart/form-data" id="addProductForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Health">Health</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Food">Food</option>
                            <option value="Prevention">Prevention</option>
                            <option value="Popular">Popular</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        <img id="image_preview" src="#" alt="Image Preview" style="display:none; max-width: 100px; margin-top: 10px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-dark">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_product.php" method="POST" enctype="multipart/form-data" id="editProductForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_product_id" name="id">
                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Category</label>
                        <select class="form-control" id="edit_category" name="category" required>
                            <option value="Health">Health</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Food">Food</option>
                            <option value="Prevention">Prevention</option>
                            <option value="Popular">Popular</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*" onchange="previewEditImage(event)">
                        <img id="edit_image_preview" src="#" alt="Product Image Preview" style="display:none; max-width: 100px; margin-top: 10px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-dark">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image preview function for Add Product modal
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Image preview function for Edit Product modal
function previewEditImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('edit_image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Handling modal data for edit
$('#editProductModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('#edit_product_id').val(button.data('id'));
    modal.find('#edit_product_name').val(button.data('product_name'));
    modal.find('#edit_description').val(button.data('description'));
    modal.find('#edit_price').val(button.data('price'));
    modal.find('#edit_stock').val(button.data('stock'));
    modal.find('#edit_category').val(button.data('category'));
    var imageUrl = button.data('image_url');
    
    if (imageUrl) {
        modal.find('#edit_image_preview').attr('src', '../uploads/' + imageUrl).show();
    } else {
        modal.find('#edit_image_preview').hide();
    }
});
</script>

</body>
</html>