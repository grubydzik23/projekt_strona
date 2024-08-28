<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Przekierowanie do logowania, jeśli użytkownik nie jest zalogowany jako admin
    exit();
}
include 'config.php'; // Załączenie pliku konfiguracyjnego

$error_message = ''; // Inicjalizacja komunikatu błędu
$success_message = ''; // Inicjalizacja komunikatu sukcesu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Odbieranie danych z formularza
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Sprawdzenie, czy przesłany plik jest obrazem
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error_message .= "Plik nie jest obrazem. ";
        $uploadOk = 0;
    }

    // Sprawdzenie, czy plik już istnieje
    if (file_exists($target_file)) {
        $error_message .= "Przepraszamy, plik już istnieje. ";
        $uploadOk = 0;
    }

    // Sprawdzenie rozmiaru pliku
    if ($_FILES["image"]["size"] > 5000000) { // 5MB
        $error_message .= "Przepraszamy, Twój plik jest za duży. ";
        $uploadOk = 0;
    }

    // Dozwolone formaty plików
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        $error_message .= "Przepraszamy, dozwolone są tylko pliki JPG, JPEG, PNG i GIF. ";
        $uploadOk = 0;
    }

    // Sprawdzenie, czy $uploadOk nie zostało ustawione na 0 przez błąd
    if ($uploadOk == 0) {
        $error_message .= "Przepraszamy, Twój plik nie został przesłany. ";
    } else {
        // Jeśli wszystko jest ok, próba przesłania pliku
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $description, $price, $category, $target_file);
            if ($stmt->execute()) {
                $success_message = "Produkt został dodany pomyślnie.";
            } else {
                $error_message = "Błąd: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Przepraszamy, wystąpił błąd podczas przesyłania Twojego pliku.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl"> <!-- Ustawienie języka na polski -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Produkt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .card {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 10px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 10px;
        }
        .success-message {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Home</a>
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
        <h1 class="text-center mb-4">Dodaj Produkt</h1>
        <?php if(isset($error_message) && !empty($error_message)): ?>
            <div class="alert alert-danger error-message" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success success-message" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Nazwa Produktu</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Opis</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Cena</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategoria</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Zdjęcie</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj Produkt</button>
        </form>
    </div>
</div>
</body>
</html>
