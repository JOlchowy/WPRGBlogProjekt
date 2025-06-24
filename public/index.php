<?php
require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';
require_once '../config/auth.php';

$db = new Database();
$post = new Post($db);

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$posts = $post->getAllPosts($limit, $offset);

$total_posts = count($post->getAllPosts(1000000, 0));
$total_pages = ceil($total_posts / $limit);

$admin_email = "admin@test.pl";

if (isset($_POST['kontakt_submit'])) {
    $od_kogo = trim($_POST['kontakt_email']);
    $tresc = trim($_POST['kontakt_wiadomosc']);

    if (!filter_var($od_kogo, FILTER_VALIDATE_EMAIL)) {
        $kontakt_error = "Niepoprawny adres e-mail.";
    } elseif (empty($tresc)) {
        $kontakt_error = "Treść wiadomości nie może być pusta.";
    } else {
        $wiadomosc = "Data: " . date("Y-m-d H:i:s") . "\nOd: $od_kogo\nTreść:\n$tresc\n\n";
        file_put_contents("../messages/kontakt.txt", $wiadomosc, FILE_APPEND);

        $kontakt_sukces = true;
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Blog</h1>

<div style="margin-bottom: 20px;">
    <?php if (isLoggedIn()): ?>
        Witaj, <?php echo htmlspecialchars($_SESSION['email']); ?> |
        <a href="<?php echo BASE_URL; ?>/public/logout.php">Wyloguj się</a>

        <?php if (isAuthor() || isAdmin()): ?>
            | <a href="<?php echo BASE_URL; ?>/public/add_post.php">Dodaj nowy post</a>
        <?php endif; ?>

        <?php if (isAdmin()): ?>
            | <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Panel administracyjny</a>
        <?php endif; ?>

    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/public/login.php">Zaloguj się</a> |
        <a href="<?php echo BASE_URL; ?>/public/register.php">Zarejestruj się</a>
    <?php endif; ?>

</div>

<h2>Ostatnie posty</h2>

<?php if ($posts): ?>
    <ul>
        <?php foreach ($posts as $single_post): ?>
            <li>
                <h3><?php echo htmlspecialchars($single_post['title']); ?></h3>
                <p><small>Dodano: <?php echo $single_post['created_at']; ?></small></p>

                <?php if (!empty($single_post['image_path'])): ?>
                    <img src="<?php echo BASE_URL . '/public/' . $single_post['image_path']; ?>" alt="Obrazek do postu"
                         width="300"><br>
                <?php endif; ?>

                <p><?php echo nl2br(htmlspecialchars($single_post['body'])); ?></p>
                <a href="<?php echo BASE_URL; ?>/public/post.php?id=<?php echo $single_post['id']; ?>">Czytaj więcej</a>

                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin() || (isAuthor() && $single_post['user_id'] == $_SESSION['user_id'])): ?>
                        | <a href="<?php echo BASE_URL; ?>/admin/edit_post.php?id=<?php echo $single_post['id']; ?>">Edytuj</a>
                        | <a href="<?php echo BASE_URL; ?>/admin/delete_post.php?id=<?php echo $single_post['id']; ?>"
                             onclick="return confirm('Czy na pewno chcesz usunąć ten post?')">Usuń</a>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Brak postów do wyświetlenia.</p>
<?php endif; ?>

<div style="margin-top: 20px;">
    <?php if ($page > 1): ?>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=<?php echo $page - 1; ?>">Poprzednia</a>
    <?php endif; ?>

    <?php if ($page < $total_pages): ?>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=<?php echo $page + 1; ?>">Następna</a>
    <?php endif; ?>
</div>
<hr>
<h2>Skontaktuj się z autorem bloga</h2>

<?php if (isset($kontakt_sukces)): ?>
    <p style="color: green;">Wiadomość została wysłana!</p>
<?php elseif (isset($kontakt_error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($kontakt_error); ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="kontakt_email">Twój e-mail:</label>
    <input type="email" name="kontakt_email" required><br><br>

    <label for="kontakt_wiadomosc">Treść wiadomości:</label><br>
    <textarea name="kontakt_wiadomosc" required></textarea><br><br>

    <input type="submit" name="kontakt_submit" value="Wyślij wiadomość">
</form>
</body>
</html>
