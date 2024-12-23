<h1>Поступившие заказы:</h1>
<a href='/admin'>Назад в админку</a>
<hr>

<?php
require_once __DIR__ . '/../../core/init.php';

$orders = Eshop::getOrders();

foreach ($orders as $order) {
    echo "<h2>Заказ номер: {$order['order_id']}</h2>";
    echo "<p><b>Заказчик</b>: {$order['customer']}</p>";
    echo "<p><b>Email</b>: {$order['email']}</p>";
    echo "<p><b>Телефон</b>: {$order['phone']}</p>";
    echo "<p><b>Адрес доставки</b>: {$order['address']}</p>";
    echo "<p><b>Дата размещения заказа</b>: {$order['created']}</p>";

    $orderId = $order['order_id'];
    $orderedItems = Eshop::getOrderedItems($orderId); 

    echo "<h3>Купленные товары:</h3>";
    echo "<table>
            <tr>
                <th>N п/п</th>
                <th>Название книги</th>
                <th>Автор</th>
                <th>Год издания</th>
                <th>Итоговая цена</th>
                <th>Количество</th>
            </tr>";

    $totalOrderPrice = 0;

    foreach ($orderedItems as $index => $item) {
        $totalPriceForItem = $item['Цена'] * $item['Количество'];
        $totalOrderPrice += $totalPriceForItem;

        echo "<tr>
                <td>" . ($index + 1) . "</td>
                <td>{$item['Название книги']}</td>
                <td>{$item['Автор']}</td>
                <td>{$item['Год издания']}</td>
                <td>{$totalPriceForItem}</td>
                <td>{$item['Количество']}</td>
              </tr>";
    }

    echo "</table>";
    echo "<p><b>Общая сумма:</b> {$totalOrderPrice} руб.</p>";
}
?>