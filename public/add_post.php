<?php

require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';
require_once '../config/auth.php';

if (!isLoggedIn() || (!isAuthor() && !isAdmin())) {
    header("Location: " . BASE_URL . "/public/login.php");
    exit;
}

$db = new Database();
$post = new Post($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $body = $_POST['body'];
    $image_path = null;

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

    $post->createPost($_SESSION['user_id'], $title, $body, $image_path);

    header("Location: " . BASE_URL . "/public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Post</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>
<h1>Dodaj nowy post</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Tytuł:</label>
    <input type="text" name="title" required><br><br>

    <label for="body">Treść:</label>
    <textarea name="body" required></textarea><br><br>

    <label for="image">Obrazek:</label>
    <input type="file" name="image"><br><br>

    <input type="submit" value="Dodaj Post">
</form>

<p><a href="<?php echo BASE_URL; ?>/public/index.php">Powrót do strony głównej</a></p>
</body>
</html>
