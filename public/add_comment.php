<?php
require_once '../config/db_config.php';
require_once '../classes/Database.php';
require_once '../classes/Post.php';
require_once '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['body'])) {
    $db = new Database();
    $body = trim($_POST['body']);
    $post_id = (int)$_POST['post_id'];
    $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
    $guest_name = isLoggedIn() ? $_SESSION['email'] : 'Gość';

    $sql = "INSERT INTO comments (post_id, user_id, guest_name, body) VALUES (:post_id, :user_id, :guest_name, :body)";
    $db->execute($sql, [
        'post_id' => $post_id,
        'user_id' => $user_id,
        'gues_name' => $guest_name,
        'body' => $body
    ]);

    header("Location: " . BASE_URL . "/public/post.php?id=" . $post_id);
    exit;
}
?>
