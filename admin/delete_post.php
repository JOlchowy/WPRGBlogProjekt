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

    if (isAdmin() || (isAuthor() && $post_data['user_id'] == $_SESSION['user_id'])) {
        $post->deletePost($_GET['id']);
    }
}

header("Location: " . BASE_URL . "/public/index.php");
exit;
?>
