<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php'; // Załączenie pliku konfiguracyjnego bazy danych

// Obsługa usuwania produktu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

// Pobranie wszystkich produktów z bazy danych
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Produktami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Strona Glowna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_product.php">Dodaj produkt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Wyloguj sie</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="text-center">Zarządzanie Produktami</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Opis</th>
            <th>Cena</th>
            <th>Kategoria</th>
            <th>Zdjęcie</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['category'] ?></td>
                <td><img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" width="50"></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger">Usuń</button>
                    </form>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-primary">Edytuj</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
