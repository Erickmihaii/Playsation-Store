<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch all products from the database
$products = get_all_products();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayStation Store</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>PlayStation Store</h1>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-button">Log Out</button>
            </form>
        </header>

        <!-- Button to go to Add New Product page -->
        <section class="add-product-section">
            <a href="create_product.php" class="add-product-button">Add New Product</a>
        </section>

        <!-- Category Filter Section -->
        <section class="category-filter">
            <h2>Select a Category</h2>
            <form action="category.php" method="get">
                <label for="category">Choose a category:</label>
                <select name="category" id="category">
                    <?php
                    $sql = "SELECT category_id, name FROM categories";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <option value="<?= $row['category_id']; ?>">
                            <?= htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="filter-button">Filter Products</button>
            </form>
        </section>

        <!-- Product List -->
        <section class="product-list">
            <h2>Our Featured Products</h2>
            <div class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <h3><?= htmlspecialchars($product['name']); ?></h3>

                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?> Image" class="product-image">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>

                        <p><strong>Description:</strong> <?= htmlspecialchars($product['description']); ?></p>
                        <p><strong>Release Date:</strong> <?= htmlspecialchars($product['release_date']); ?></p>
                        <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock']); ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>

                        <!-- Edit Button -->
                        <?php if (isset($product['product_id'])): ?>
                            <form action="edit_product.php" method="get">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
                                <button type="submit">Edit</button>
                            </form>
                        <?php endif; ?>

                        <!-- Delete Button -->
                        <?php if (isset($product['product_id'])): ?>
                            <form action="delete_product.php" method="post">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>

</html>