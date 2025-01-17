<?php
require 'db.php';

$category_id = $_GET['category'] ?? null;

if ($category_id === null) {
    die("Category not specified.");
}

$sql = "SELECT * FROM products WHERE category_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $category_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products</title>
    <link rel="stylesheet" href="category.css"> <!-- Conectează fișierul CSS -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Products in Category <?= htmlspecialchars($category_id); ?></h1>
            <a href="index.php" class="back-button">Back to Home</a>
        </header>

        <section class="product-list">
            <?php if (count($products) > 0): ?>
                <div class="products">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                            <p><strong>Description:</strong> <?= htmlspecialchars($product['description']); ?></p>
                            <p><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products found for this category.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>