<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function login($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
}

function logout()
{
    session_destroy();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin';
}

function isAuthor()
{
    return isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'author';
}

?>
