<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);

    // Verifică dacă produsul există și obține calea imaginii
    $sql = "SELECT image FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($product) {
        // Șterge fișierul imaginii dacă există
        if (!empty($product['image']) && file_exists($product['image'])) {
            unlink($product['image']);
        }

        // Șterge produsul din baza de date
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $product_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Product deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting product. Please try again.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Product not found.";
    }

    header("Location: index.php");
    exit;
}
?>
