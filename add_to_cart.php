<?php
// Włącz raportowanie wszystkich błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'config.php'; // Załaduj plik konfiguracyjny z ustawieniami bazy danych

// Sprawdź czy użytkownik jest zalogowany i ustaw sesję user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("Brak dostępu do identyfikatora użytkownika.");
}

// Sprawdź czy przekazano product_id przez POST
if (!isset($_POST['product_id'])) {
    die("Brak identyfikatora produktu.");
}

// Przypisz zmienne z POST i zabezpiecz przed SQL Injection
$product_id = intval($_POST['product_id']);

// Sprawdź, czy zmienne $product_id są poprawnymi liczbami całkowitymi (int)
if ($product_id <= 0) {
    die("Nieprawidłowe dane wejściowe.");
}

// Przygotuj zapytanie SQL do wstawienia do koszyka z prepared statement
$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $product_id);

// Wykonaj zapytanie do bazy danych
if ($stmt->execute()) {
    // Przekieruj użytkownika do koszyka po dodaniu produktu
    header("Location: cart.php");
    exit();
} else {
    // Obsłuż błąd jeśli zapytanie się nie powiodło
    echo "Błąd podczas dodawania produktu do koszyka: " . $stmt->error;
}

// Zamknij prepared statement i połączenie z bazą danych
$stmt->close();
$conn->close();
?>
