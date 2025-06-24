<?php

class Post
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllPosts($limit = 5, $offset = 0)
    {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createPost($user_id, $title, $body, $image_path = null)
    {
        $sql = "INSERT INTO posts (user_id, title, body, image_path) VALUES (:user_id, :title, :body, :image_path)";
        return $this->db->execute($sql, ['user_id' => $user_id, 'title' => $title, 'body' => $body, 'image_path' => $image_path]);
    }

    public function getPostById($id)
    {
        $sql = "SELECT * FROM posts WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    public function updatePost($id, $title, $body, $image_path = null)
    {
        $sql = "UPDATE posts SET title = :title, body = :body, image_path = :image_path WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id, 'title' => $title, 'body' => $body, 'image_path' => $image_path]);
    }

    public function deletePost($id)
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    public function getComments($post_id)
    {
        $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at ASC";
        return $this->db->query($sql, ['post_id' => $post_id]);
    }

    public function getPreviousPost($current_id)
    {
        $sql = "SELECT id, title FROM posts WHERE id < :id ORDER BY id DESC LIMIT 1";
        return $this->db->query($sql, ['id' => $current_id]);
    }

    public function getNextPost($current_id)
    {
        $sql = "SELECT id, title FROM posts WHERE id > :id ORDER BY id ASC LIMIT 1";
        return $this->db->query($sql, ['id' => $current_id]);
    }
}

?>
