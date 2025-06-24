<?php
require_once '../config/db_config.php';
require_once '../config/auth.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: " . BASE_URL . "/public/login.php");
    exit;
}


$db = new Database();
$post = new Post($db);

$posts = $post->getAllPosts(1000, 0);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Panel administracyjny</h1>

<p>Witaj, <?php echo htmlspecialchars($_SESSION['email']); ?> (<?php echo $_SESSION['role']; ?>)</p>

<p><a href="<?php echo BASE_URL; ?>/public/index.php">Powrót na stronę główną</a> |
    <a href="<?php echo BASE_URL; ?>/public/logout.php">Wyloguj się</a></p>

<hr>

<h2>Posty</h2>

<?php if (isAdmin() || isAuthor()): ?>
    <a href="<?php echo BASE_URL; ?>/public/add_post.php">Dodaj nowy post</a>
<?php endif; ?>

<table class="tabela-postow">
    <thead>
    <tr>
        <th>Tytuł</th>
        <th>Autor (user_id)</th>
        <th>Akcje</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($posts as $single_post): ?>
        <tr>
            <td><?php echo htmlspecialchars($single_post['title']); ?></td>
            <td><?php echo $single_post['user_id']; ?></td>
            <td>
                <?php if (isAdmin() || (isAuthor() && $single_post['user_id'] == $_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/edit_post.php?id=<?php echo $single_post['id']; ?>">Edytuj</a>
                <?php endif; ?>

                <?php if (isAdmin() || (isAuthor() && $single_post['user_id'] == $_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/delete_post.php?id=<?php echo $single_post['id']; ?>"
                       onclick="return confirm('Czy na pewno chcesz usunąć ten post?')">Usuń</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
