<?php
require_once __DIR__ . '/../../core/init.php';
require_once __DIR__ . '/../../core/Book.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = Cleaner::str($_POST['title']);
    $author = Cleaner::str($_POST['author']);
    $price = Cleaner::uint($_POST['price']);
    $pubyear = Cleaner::uint($_POST['pubyear']);

    $book = new Book($title, $author, $price, $pubyear);

    if (Eshop::addItemToCatalog($book)) {

        session_start();
        $_SESSION['success_message'] = 'Книга успешно добавлена в каталог!';
        header('Location: /admin/add_item_to_catalog');
        exit;
    } else {
        echo 'Ошибка при добавлении товара в каталог';
    }
}
?>