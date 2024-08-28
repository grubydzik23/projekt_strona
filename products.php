<?php
session_start();
include 'config.php';

$category = $_GET['category']; // Pobranie kategorii produktów z parametru URL

$sql = "SELECT * FROM products WHERE category='$category'"; // Zapytanie SQL pobierające produkty z danej kategorii
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row; // Zapisanie wyników zapytania do tablicy $products
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
    <h2>Produkty w kategorii <?= htmlspecialchars($category) ?></h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['name'] ?></h5>
                        <p class="card-text"><?= $product['description'] ?></p>
                        <p class="card-text">$<?= $product['price'] ?></p>
                        <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-primary">Dodaj do koszyka</a>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>