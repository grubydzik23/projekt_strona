<?php
session_start();
include 'config.php'; // Załączenie pliku konfiguracyjnego

$user_id = $_SESSION['user_id']; // Pobranie ID użytkownika z sesji
$sql = "SELECT products.id, products.name, products.description, products.price, products.image FROM cart 
        JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?"; // Zapytanie SQL do pobrania produktów z koszyka użytkownika
$stmt = $conn->prepare($sql); // Przygotowanie zapytania SQL
$stmt->bind_param("i", $user_id); // Powiązanie parametru zapytania
$stmt->execute(); // Wykonanie zapytania
$result = $stmt->get_result(); // Pobranie wyników zapytania
$cart_products = [];
while ($row = $result->fetch_assoc()) {
    $cart_products[] = $row; // Zapisanie wyników do tablicy
}
$stmt->close(); // Zamknięcie statement

?>

<!DOCTYPE html>
<html lang="pl"> <!-- Ustawienie języka na polski -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twój Koszyk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Strona główna</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Wyloguj</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Zaloguj</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="text-center">Twój Koszyk</h1>
    <?php if (empty($cart_products)): ?>
        <p class="text-center">Twój koszyk jest pusty.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cart_products as $product): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name'] ?></h5>
                            <p class="card-text"><?= $product['description'] ?></p>
                            <p class="card-text">Cena: <?= $product['price'] ?></p>
                            <form method="POST" action="remove_from_cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-danger">Usuń z koszyka</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
