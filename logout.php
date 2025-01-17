<?php
session_start();

// Distruge toate datele din sesiune
session_unset();
session_destroy();

// Redirecționează utilizatorul către pagina de login
header("Location: login.php");
exit;
?>