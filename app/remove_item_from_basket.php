<?php
require_once __DIR__ . '/../core/init.php'; 

if (isset($_GET['title'])) {
    $bookTitle = $_GET['title'];
    Eshop::removeItemFromBasket($bookTitle);
    header('Location: /basket'); 
    exit();
}
?>