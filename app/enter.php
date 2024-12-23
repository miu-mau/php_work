<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel='stylesheet' href='/css/style.css'>
</head>
<body>
    <h1>Вход в систему</h1>
    
    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>

<?php
    require_once __DIR__ . '/../core/init.php';
    require_once __DIR__ . '/../core/User.php'; 
    require_once __DIR__ . '/../core/Eshop.class.php';     

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['login'];
        $password = $_POST['password'];
    
        
        $user = new User($username, $password, '');
        echo "<script>console.log('Создан пользователь: " . json_encode($user->toArray()) . "');</script>";
        if (Eshop::logIn($user)) {
            header('Location: /catalog');
            exit();
        } else {
            echo 'Неверный логин или пароль.';
        }
    }
?>