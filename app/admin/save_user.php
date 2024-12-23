<?php
require_once __DIR__ . '/../../core/init.php';
require_once __DIR__ . '/../../core/User.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['login'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $user = new User($username, $password, $email);
    
    if (!Eshop::userCheck($user)) {
        Eshop::userAdd($user);
        header('Location: /admin');
        exit();
    } else {
        echo 'Пользователь с таким логином уже существует.';
    }
}
?>