<?php

require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';
require_once '../classes/Comment.php';
require_once '../config/auth.php';

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "/public/index.php");
    exit;
}

$post_id = (int)$_GET['id'];

$db = new Database();
$post = new Post($db);
$comment = new Comment($db);
$prev_post = $post->getPreviousPost($post_id);
$next_post = $post->getNextPost($post_id);

$post_data = $post->getPostById($post_id)[0];

$comments = $comment->getCommentsByPostId($post_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
    $author_name = isLoggedIn() ? $_SESSION['email'] : $_POST['author_name'];
    $body = trim($_POST['body']);

    if (!empty($body)) {
        $comment->addComment($post_id, $user_id, $author_name, $body);
    }

    header("Location: " . BASE_URL . "/public/post.php?id=" . $post_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
    <title><?php echo htmlspecialchars($post_data['title']); ?></title>
</head>
<body>
<h1><?php echo htmlspecialchars($post_data['title']); ?></h1>
<p><small>Dodano: <?php echo $post_data['created_at']; ?></small></p>
<p><?php echo nl2br(htmlspecialchars($post_data['body'])); ?></p>

<?php if (!empty($post_data['image_path'])): ?>
    <img src="<?php echo BASE_URL . '/public/' . htmlspecialchars($post_data['image_path']); ?>" alt="Obrazek do postu"
         width="300">
<?php endif; ?>

<h2>Komentarze</h2>

<form method="POST">
    <?php if (!isLoggedIn()): ?>
        <label for="author_name">Twoje imię (dla gości):</label>
        <input type="text" name="author_name" required><br><br>
    <?php endif; ?>

    <label for="body">Treść komentarza:</label><br>
    <textarea name="body" required></textarea><br><br>
    <input type="submit" value="Dodaj komentarz">
</form>

<h3>Ostatnie komentarze:</h3>
<ul>
    <?php foreach ($comments as $comment_data): ?>
        <li>
            <strong>
                <?php echo htmlspecialchars($comment_data['author_name'] ?: 'Gość'); ?>
            </strong>:
            <p><?php echo nl2br(htmlspecialchars($comment_data['body'])); ?></p>
            <em>Dodano: <?php echo $comment_data['created_at']; ?></em>
        </li>
    <?php endforeach; ?>
</ul>
<div style="margin-top:20px;">
    <?php if ($prev_post): ?>
        <a href="<?php echo BASE_URL; ?>/public/post.php?id=<?php echo $prev_post[0]['id']; ?>">&larr; Poprzedni
            post: <?php echo htmlspecialchars($prev_post[0]['title']); ?></a>
    <?php endif; ?>

    <?php if ($next_post): ?>
        <a href="<?php echo BASE_URL; ?>/public/post.php?id=<?php echo $next_post[0]['id']; ?>" style="float:right;">Następny
            post: <?php echo htmlspecialchars($next_post[0]['title']); ?> &rarr;</a>
    <?php endif; ?>
</div>


<p><a href="<?php echo BASE_URL; ?>/public/index.php">Powrót do listy postów</a></p>
</body>
</html>
