<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}
require_once CORE_DIR . '/../core/Order.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['items']) || empty($_POST['items'])) {

        header('Location: /basket'); 
        exit();
    }

    $customer = $_POST['customer'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $items = json_decode($_POST['items'], true); 


    foreach ($items as $item) {
        if (!isset($item['id_catalog'], $item['quantity'])) {

            echo 'Ошибка: отсутствуют необходимые данные для товара.';
            exit();
        }
    }
    try {
        $order = new Order($customer, $email, $phone, $address, $items);
        Eshop::saveOrder($order);
        header('Location: /catalog'); 
        exit();
    } catch (Exception $e) {
        echo 'Произошла ошибка при оформлении заказа. Пожалуйста, попробуйте позже.';
    }
}
?>