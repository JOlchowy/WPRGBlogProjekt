<?php

require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';
require_once '../config/auth.php';

if (!isLoggedIn()) {
    header("Location: " . BASE_URL . "/public/login.php");
    exit;
}

$db = new Database();
$post = new Post($db);

if (isset($_GET['id'])) {
    $post_data = $post->getPostById($_GET['id'])[0];

    if (!(isAdmin() || (isAuthor() && $post_data['user_id'] == $_SESSION['user_id']))) {
        header("Location: " . BASE_URL . "/public/index.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $image_path = $post_data['image_path'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../public/uploads/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = time() . "_" . basename($_FILES['image']['name']);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = "uploads/" . $file_name;
            }
        }

        $post->updatePost($_GET['id'], $title, $body, $image_path);
        header("Location: " . BASE_URL . "/public/index.php");
        exit;
    }
} else {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Post</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Edytuj Post</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Tytuł:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post_data['title']); ?>" required><br><br>

    <label for="body">Treść:</label>
    <textarea name="body" required><?php echo htmlspecialchars($post_data['body']); ?></textarea><br><br>

    <?php if (!empty($post_data['image_path'])): ?>
        <img src="<?php echo BASE_URL . '/public/' . $post_data['image_path']; ?>" alt="Obrazek do postu" width="200">
        <br><br>
    <?php endif; ?>

    <label for="image">Zmień obrazek:</label>
    <input type="file" name="image"><br><br>

    <input type="submit" value="Zaktualizuj Post">
</form>

<p><a href="<?php echo BASE_URL; ?>/public/index.php">Powrót do panelu</a></p>
</body>
</html>
