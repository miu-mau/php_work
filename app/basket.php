<?php
require_once __DIR__ . '/../core/init.php'; 

$items = Eshop::getItemsFromBasket();
?>

<p>Вернуться в <a href='/catalog'>каталог</a></p>
<h1>Ваша корзина</h1>
<table>
<tr>
    <th>N п/п</th>
    <th>Название</th>
    <th>Автор</th>
    <th>Год издания</th>
    <th>Первоночальная цена, руб.</th>
    <th>Количество</th>
    <th>Итоговая цена, руб.</th>
    <th>Удалить</th>
</tr>

<?php
foreach ($items as $index => $item) {
    $totalPriceForItem = $item['price'] * $item['quantity']; 
    echo "<tr>
            <td>" . ($index + 1) . "</td>
            <td>{$item['title']}</td>
            <td>{$item['author']}</td>
            <td>{$item['pubyear']}</td>
            <td>{$item['price']}</td>
            <td>{$item['quantity']}</td>
            <td>{$totalPriceForItem}</td>
            <td><a href='remove_item_from_basket?title={$item['title']}'>Удалить</a></td>
          </tr>";
}
?>
</table>

<p>Общая сумма: <?php echo $basket->getTotalPrice(); ?> руб.</p>

<p>Всего товаров в корзине: <?php echo Eshop::getCountItemsInBasket(); ?></p>
<div style="text-align:center">
    <input type="button" value="Оформить заказ!" onclick="location.href='/create_order'" />
</div>