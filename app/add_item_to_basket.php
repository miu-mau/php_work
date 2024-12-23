<?php
require_once __DIR__ . '/../core/init.php';
require_once __DIR__ . '/../core/Book.php'; 

if (isset($_GET['title'])) {
    $bookTitle = $_GET['title'];
    $book = Book::findByTitle($bookTitle); 
    if ($book) {
        Eshop::addItemToBasket($book); 
        header('Location: /catalog');
        exit();
    }
}
?>