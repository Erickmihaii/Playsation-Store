<?php
session_start();
require 'db.php'; // Include fișierul pentru conexiunea la baza de date

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preluare date din formular și validare
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $release_date = trim($_POST['release_date']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);



    // Validare suplimentară pentru `release_date`
    if (empty($release_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $release_date)) {
        $_SESSION['error'] = 'Invalid or missing release date.';
        header('Location: create_product.php');
        exit;
    }

    // Gestionare imagine încărcată
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Creare director dacă nu există
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . uniqid() . '_' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = $image_path;
        } else {
            $_SESSION['error'] = 'Image upload failed.';
            header('Location: create_product.php');
            exit;
        }
    }

    // Inserare produs în baza de date
    $sql = "INSERT INTO products (name, description, image, release_date, stock, category_id, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sssiiid", $name, $description, $image, $release_date, $stock, $category_id, $price);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Product added successfully!';
    } else {
        $_SESSION['error'] = 'Error adding product: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    header('Location: create_product.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayStation Store - Add Product</title>
    <link rel="stylesheet" href="create_product.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>PlayStation Store</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="logout.php">Log Out</a>
            </nav>
        </header>

        <main>
            <section class="add-product">
                <h2>Add New Product</h2>

                <!-- Success message -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert success">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Error message -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert error">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="create_product.php" method="post" enctype="multipart/form-data">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" required>

                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required></textarea>

                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image" accept="image/*">

                    <label for="release_date">Release Date:</label>
                    <input type="date" name="release_date" id="release_date" required>

                    <label for="stock">Stock:</label>
                    <input type="number" name="stock" id="stock" required>

                    <label for="category_id">Category:</label>
                    <select name="category_id" id="category_id" required>
                        <?php
                        $categories = mysqli_query($conn, "SELECT * FROM categories");
                        if (mysqli_num_rows($categories) > 0) {
                            while ($category = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $category['category_id']; ?>">
                                    <?= htmlspecialchars($category['name']); ?>
                                </option>
                        <?php endwhile;
                        } else {
                            echo '<option disabled>No categories available</option>';
                        }
                        ?>
                    </select>

                    <label for="price">Price:</label>
                    <input type="number" step="0.01" name="price" id="price" required>

                    <button type="submit" class="btn-submit">Add Product</button>
                </form>
            </section>
        </main>
    </div>
</body>

</html>