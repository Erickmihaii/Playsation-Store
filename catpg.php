<?php
// Include the database functions
require 'db.php';

// Get the selected category from the query string
$selectedCategoryId = $_GET['category'] ?? '';

// Fetch products by category using the database function
$filteredProducts = get_products_by_category($selectedCategoryId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in Category</title>
</head>
<body>
    <h1>Products in 
        <?php
        // Get the category name for the selected ID
        $categoryName = '';
        if ($selectedCategoryId) {
            $sql = "SELECT name FROM categories WHERE category_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $selectedCategoryId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $categoryRow = mysqli_fetch_assoc($result);
            $categoryName = $categoryRow['name'] ?? 'Unknown Category';
            mysqli_stmt_close($stmt);
        }
        echo htmlspecialchars($categoryName);
        ?>
    </h1>

    <div class="product-list">
        <?php if (count($filteredProducts) > 0): ?>
            <?php foreach ($filteredProducts as $product): ?>
                <div class='product'>
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?> Image" />
                    <p><strong>Description:</strong> <?= htmlspecialchars($product['description']); ?></p>
                    <p><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found in this category.</p>
        <?php endif; ?>
    </div>
</body>
</html>
