<?php

class Comment
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCommentsByPostId($post_id)
    {
        $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC";
        return $this->db->query($sql, ['post_id' => $post_id]);
    }

    public function addComment($post_id, $user_id, $author_name, $body)
    {
        $sql = "INSERT INTO comments (post_id, user_id, author_name, body) VALUES (:post_id, :user_id, :author_name, :body)";
        return $this->db->execute($sql, ['post_id' => $post_id, 'user_id' => $user_id, 'author_name' => $author_name, 'body' => $body]);
    }
}

?>
