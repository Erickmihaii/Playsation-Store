<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database functions
require 'db.php';

// Get the product ID from the query string
$productIdToEdit = $_GET['product_id'] ?? '';
$productToEdit = get_product_by_id($productIdToEdit);

if (!$productToEdit) {
    die("Product not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create an array to hold updated data
    $updatedData = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => (float)$_POST['price'],
        'stock' => (int)$_POST['stock'],
        'category_id' => (int)$_POST['category_id'],
        'image' => $productToEdit['image'] // Default to existing image path
    ];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $imagePath = 'images/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $updatedData['image'] = $imagePath;
        }
    }

    // Update the product in the database
    $updateSuccess = update_product($productIdToEdit, $updatedData);

    if ($updateSuccess) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating the product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - <?= htmlspecialchars($productToEdit['name']); ?></title>
</head>
<body>
    <h1>Edit Product - <?= htmlspecialchars($productToEdit['name']); ?></h1>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($productToEdit['name']); ?>" required>
        <br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($productToEdit['description']); ?></textarea>
        <br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($productToEdit['price']); ?>" required>
        <br>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($productToEdit['stock']); ?>" required>
        <br>

        <label for="category_id">Category ID:</label>
        <input type="number" name="category_id" id="category_id" value="<?= htmlspecialchars($productToEdit['category_id']); ?>" required>
        <br>

        <label for="image">Image:</label>
        <input type="file" name="image" id="image">
        <br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>