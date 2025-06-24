<?php
$servername = "localhost";  // Adres serwera MySQL
$username = "root";  // Nazwa użytkownika
$password = "";  // Hasło użytkownika
$dbname = "blog";  // Nazwa bazy danych

// Tworzymy połączenie
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzamy połączenie
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

echo "Połączenie udane!";
?>