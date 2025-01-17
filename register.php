<?php
session_start();
require 'db.php'; // Include fișierul pentru conexiunea la baza de date

// Procesăm formularul de înregistrare
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validăm parolele
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Verificăm dacă numele de utilizator există deja
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_fetch_assoc($result)) {
            $error = "Username is already taken.";
        } else {
            // Criptăm parola
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Inserăm utilizatorul în baza de date
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                // Înregistrare reușită, redirecționăm către pagina de login
                header("Location: login.php");
                exit;
            } else {
                $error = "Error during registration. Please try again.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css"> <!-- Conectează fișierul CSS -->
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="register-btn">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
