<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'playstation_store';

// Conectare la baza de date
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Obține toate produsele din baza de date
 */
function get_all_products()
{
    global $conn;
    $sql = "SELECT product_id, name, description, price, release_date, stock, category_id, image FROM products";
    $result = mysqli_query($conn, $sql);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $product = [
            "product_id" => (int)$row['product_id'],
            "name" => $row['name'],
            "description" => $row['description'],
            "price" => (float)$row['price'],
            "release_date" => $row['release_date'],
            "stock" => (int)$row['stock'],
            "category_id" => (int)$row['category_id'],
            "image" => $row['image']
        ];
        $products[] = $product;
    }
    return $products;
}

/**
 * Obține produsele pe baza unui ID de categorie
 */
function get_products_by_category($category_id)
{
    global $conn;
    $sql = "SELECT product_id, name, description, price, release_date, stock, image 
            FROM products WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $product = [
            "product_id" => (int)$row['product_id'],
            "name" => $row['name'],
            "description" => $row['description'],
            "price" => (float)$row['price'],
            "release_date" => $row['release_date'],
            "stock" => (int)$row['stock'],
            "image" => $row['image']
        ];
        $products[] = $product;
    }

    mysqli_stmt_close($stmt);
    return $products;
}

/**
 * Obține un produs pe baza numelui
 */
function get_product_by_name($name)
{
    global $conn;
    $sql = "SELECT product_id, name, description, price, release_date, stock, category_id, image 
            FROM products WHERE name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $product = mysqli_fetch_assoc($result);
    if ($product) {
        $product['product_id'] = (int)$product['product_id'];
        $product['price'] = (float)$product['price'];
        $product['stock'] = (int)$product['stock'];
        $product['category_id'] = (int)$product['category_id'];
    }

    mysqli_stmt_close($stmt);
    return $product;
}

/**
 * Obține un produs pe baza ID-ului său
 */
function get_product_by_id($productId)
{
    global $conn;
    $sql = "SELECT product_id, name, description, price, release_date, stock, category_id, image 
            FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $product = mysqli_fetch_assoc($result);
    if ($product) {
        $product['product_id'] = (int)$product['product_id'];
        $product['price'] = (float)$product['price'];
        $product['stock'] = (int)$product['stock'];
        $product['category_id'] = (int)$product['category_id'];
    }

    mysqli_stmt_close($stmt);
    return $product;
}

/**
 * Actualizează un produs pe baza numelui său
 */
function update_product($productId, $newData)
{
    global $conn;

    $sql = "UPDATE products 
            SET name = ?, description = ?, price = ?, release_date = ?, stock = ?, category_id = ?, image = ? 
            WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssdsiisi",
        $newData['name'],
        $newData['description'],
        $newData['price'],
        $newData['release_date'],
        $newData['stock'],
        $newData['category_id'],
        $newData['image'],
        $productId
    );

    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $success;
}

