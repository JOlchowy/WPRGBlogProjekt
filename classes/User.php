<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->query($sql, ['email' => $email]);
    }

    public function create($email, $password_hash, $role = 'user')
    {
        $sql = "INSERT INTO users (email, password_hash, role) VALUES (:email, :password_hash, :role)";
        return $this->db->execute($sql, [
            'email' => $email,
            'password_hash' => $password_hash,
            'role' => $role
        ]);
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user[0]['password_hash'])) {
            return $user[0];
        }
        return false;
    }

    public function updatePassword($email, $password_hash)
    {
        $sql = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
        return $this->db->execute($sql, [
            'password_hash' => $password_hash,
            'email' => $email
        ]);
    }
}

?>
