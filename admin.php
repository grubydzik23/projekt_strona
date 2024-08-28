<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Przekierowanie do logowania, jeśli użytkownik nie jest zalogowany jako admin
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl"> <!-- Ustawienie języka na polski -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Strona główna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_product.php">Dodaj Produkt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Wyloguj</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="text-center">Panel Administratora</h1>
    <p>Witaj, <?= $_SESSION['username'] ?></p> <!-- Wyświetlenie nazwy użytkownika z sesji -->
    <a href="manage_products.php" class="btn btn-primary">Zarządzaj Produktami</a> <!-- Przycisk do zarządzania produktami -->
</div>
</body>
</html>
