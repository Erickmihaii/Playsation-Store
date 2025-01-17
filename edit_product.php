<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Verifică dacă se oferă un ID valid
if (!isset($_GET['product_id'])) {
    $_SESSION['error'] = "Product ID not specified.";
    header("Location: index.php");
    exit;
}

$product_id = intval($_GET['product_id']);

// Preia datele produsului
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: index.php");
    exit;
}

// Gestionează actualizarea produsului
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $release_date = trim($_POST['release_date']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);

    // Gestionarea imaginii actualizate
    $image = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Șterge imaginea veche dacă există
            if (!empty($product['image']) && file_exists($product['image'])) {
                unlink($product['image']);
            }
            $image = $image_path;
        } else {
            $_SESSION['error'] = "Image upload failed.";
            header("Location: edit_product.php?product_id=$product_id");
            exit;
        }
    }

    // Actualizează produsul în baza de date
    $sql = "UPDATE products SET name = ?, description = ?, image = ?, release_date = ?, stock = ?, category_id = ?, price = ? 
            WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssiiidi", $name, $description, $image, $release_date, $stock, $category_id, $price, $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating product. Please try again.";
    }

    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit_product.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Edit Product</h1>
        </header>
        <form action="edit_product.php?product_id=<?= htmlspecialchars($product_id); ?>" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($product['description']); ?></textarea><br>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*"><br>
            <?php if (!empty($product['image'])): ?>
                <p>Current image: <img src="<?= htmlspecialchars($product['image']); ?>" alt="Product Image" width="100"></p>
            <?php endif; ?>

            <label for="release_date">Release Date:</label>
            <input type="date" name="release_date" id="release_date" value="<?= htmlspecialchars($product['release_date']); ?>" required><br>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock']); ?>" required><br>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <?php
                $categories = mysqli_query($conn, "SELECT * FROM categories");
                while ($category = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $category['category_id']; ?>" <?= $product['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required><br>

            <button type="submit">Save Changes</button>
        </form>
        <a href="index.php">Cancel</a>
    </div>
</body>

</html>
