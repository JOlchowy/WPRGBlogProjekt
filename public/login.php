<?php
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../config/auth.php';
require_once '../config/db_config.php';

$db = new Database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authenticatedUser = $user->authenticate($email, $password);

    if ($authenticatedUser) {
        login($authenticatedUser);

        if ($authenticatedUser['role'] === 'admin') {
            header("Location: " . BASE_URL . "/admin/dashboard.php");
            exit;
        } else {
            header("Location: " . BASE_URL . "/public/index.php");
            exit;
        }
    } else {
        $error = "Błędny e-mail lub hasło.";
    }
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Logowanie</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="email">E-mail:</label>
    <input type="email" name="email" required><br><br>

    <label for="password">Hasło:</label>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Zaloguj">
</form>

<p><a href="<?php echo BASE_URL; ?>/public/register.php">Nie masz konta? Zarejestruj się tutaj</a></p>
<p><a href="<?php echo BASE_URL; ?>/public/reset_password.php">Nie pamiętasz hasła? Zresetuj je tutaj</a></p>


<p><a href="<?php echo BASE_URL; ?>/public/index.php">Powrót na stronę główną</a></p>
</body>
</html>
