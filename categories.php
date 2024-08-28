<?php
session_start();
include 'config.php'; // Załączenie pliku konfiguracyjnego

$sql = "SELECT DISTINCT category FROM products"; // Zapytanie SQL do pobrania unikalnych kategorii produktów
$result = $conn->query($sql); // Wykonanie zapytania SQL
$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category']; // Zapisanie unikalnych kategorii do tablicy
    }
}
?>
<!DOCTYPE html>
<html lang="pl"> <!-- Ustawienie języka na polski -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Strona główna</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categories.php">Kategorie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Koszyk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Wyloguj</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <h2>Kategorie</h2>
    <ul class="list-group">
        <?php foreach ($categories as $category): ?>
            <li class="list-group-item"><a href="products.php?category=<?= $category ?>"><?= $category ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
