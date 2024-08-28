<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Przekierowanie do strony logowania, jeśli użytkownik nie jest zalogowany jako admin
    exit();
}
include 'config.php'; // Załączenie pliku konfiguracyjnego do połączenia z bazą danych

$product_id = $_GET['id']; // Pobranie ID produktu z parametru GET

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jeśli formularz został przesłany, przetwarzamy dane
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Aktualizacja danych produktu w bazie danych
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $product_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_products.php"); // Przekierowanie po zaktualizowaniu produktu do strony zarządzania produktami
    exit();
}

// Pobranie informacji o produkcie do edycji
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pl"> <!-- Ustawienie języka na polski -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Produkt</title>
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
    <div class="card">
        <h1 class="text-center mb-4">Edytuj Produkt</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nazwa Produktu</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $product['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Opis</label>
                <textarea class="form-control" id="description" name="description" required><?= $product['description'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Cena</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategoria</label>
                <input type="text" class="form-control" id="category" name="category" value="<?= $product['category'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Zapisz Zmiany</button>
        </form>
    </div>
</div>
</body>
</html>
