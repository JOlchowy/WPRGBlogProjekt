<?php

require_once '../config/db_config.php';
require_once '../classes/Database.php';

try {
    $db = new Database();
    echo "Połączenie z bazą danych działa poprawnie!";
} catch (Exception $e) {
    echo "Błąd: " . $e->getMessage();
}
?>
