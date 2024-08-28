<?php
session_start();
include 'config.php';

$product_id = $_GET['id']; // Pobranie ID produktu z parametru URL
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc(); // Pobranie szczegółowych informacji o produkcie
$stmt->close();

// Obsługa dodawania produktu do koszyka
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Pobranie ID użytkownika z sesji
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Zapytanie o liczbę użytkowników mających dany produkt w koszyku
$sql = "SELECT COUNT(*) as user_count FROM cart WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$user_count = $result->fetch_assoc()['user_count']; // Pobranie liczby użytkowników z produktem w koszyku
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<p>Liczba użytkowników mających ten produkt w koszyku: <?= $user_count ?></p>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="card">
        <h1 class="text-center"><?= $product['name'] ?></h1>
        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid">
        <p><?= $product['description'] ?></p>
        <p>Cena: <?= $product['price'] ?></p>
        <p>Liczba użytkowników mających ten produkt w koszyku: <?= $user_count ?></p>
        <form method="POST">
            <button type="submit" class="btn btn-primary">Dodaj do koszyka</button>
        </form>
    </div>
</div>
</body>
</html>
