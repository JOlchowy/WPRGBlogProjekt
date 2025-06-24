<?php
require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

$db = new Database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Niepoprawny adres e-mail.";
    } elseif ($password !== $password_confirm) {
        $error = "Hasła nie są takie same.";
    } elseif (empty($user->findByEmail($email))) {
        $error = "Nie znaleziono użytkownika o podanym e-mailu.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->updatePassword($email, $password_hash);
        $success = "Hasło zostało zresetowane. Możesz się teraz zalogować.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Resetowanie hasła</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Resetowanie hasła</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php elseif (isset($success)): ?>
    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form method="POST">
    <label for="email">Twój e-mail:</label>
    <input type="email" name="email" required><br><br>

    <label for="password">Nowe hasło:</label>
    <input type="password" name="password" required><br><br>

    <label for="password_confirm">Potwierdź hasło:</label>
    <input type="password" name="password_confirm" required><br><br>

    <input type="submit" value="Zresetuj hasło">
</form>

<p><a href="<?php echo BASE_URL; ?>/public/login.php">Powrót do logowania</a></p>
</body>
</html>
