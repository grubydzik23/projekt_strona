<?php
session_start();
include 'config.php'; // Załączenie pliku konfiguracyjnego bazy danych
$title = "Strona Produktu";
include 'head.php'; // Załączenie nagłówka HTML
include 'footer.php'; // Załączenie stopki HTML
$most_added_products = []; // Inicjalizacja pustej tablicy na najczęściej dodawane produkty

// Zapytanie SQL na pobranie najczęściej dodawanych produktów
$sql = "SELECT product_id, COUNT(product_id) as count FROM cart GROUP BY product_id ORDER BY count DESC LIMIT 10";
$result = $conn->query($sql);

// Sprawdzenie, czy zapytanie zwróciło wyniki
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        // Zapytanie o szczegóły produktu na podstawie ID
        $product_sql = "SELECT * FROM products WHERE id='$product_id'";
        $product_result = $conn->query($product_sql);
        // Sprawdzenie, czy produkt został znaleziony
        if ($product_result->num_rows > 0) {
            $most_added_products[] = $product_result->fetch_assoc(); // Dodanie produktu do tablicy
        }
    }
}

// Funkcja do pobierania liczby produktów w koszyku użytkownika
function get_cart_count($conn) {
    $count = 0;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        // Zapytanie SQL na liczbę produktów w koszyku użytkownika
        $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        // Sprawdzenie, czy zapytanie zwróciło wyniki
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count']; // Przypisanie liczby produktów do zmiennej $count
        }
    }
    return $count; // Zwrócenie liczby produktów w koszyku
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .navbar-nav .nav-link:hover {
            color: #007bff;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .jumbotron {
            background-color: #007bff;
            color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
        }
        .product-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .product-card img {
            width: 100%;
            height: auto;
        }
        .product-card .card-body {
            padding: 1.25rem;
        }
        .product-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .product-card .card-text {
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 8px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">PROJEKT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Kategorie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Wyloguj</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Zaloguj</a>
                    </li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text ms-auto">
                <a class="nav-link" href="cart.php">Koszyk <span class="badge bg-secondary"><?= get_cart_count($conn) ?></span></a>
            </span>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-4 text-center">Witaj</h1>
        <p class="lead text-center">Przeglądaj mój sklep</p>
    </div>

    <div class="container mt-5">
        <h1 class="text-center">Najczęściej Dodawane Produkty</h1>
        <div class="row">
            <?php if (count($most_added_products) > 0): ?>
                <?php foreach ($most_added_products as $product): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                <p class="card-text"><?= $product['description'] ?></p>
                                <p class="card-text">$<?= $product['price'] ?></p>
                                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">Zobacz Produkt</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Brak popularnych produktów do wyświetlenia.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
