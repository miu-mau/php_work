<h1>Оформление заказа</h1>
<p>Вернуться в <a href='/catalog'>каталог</a></p>

<?php
require_once __DIR__ . '/../core/init.php';

$items = Eshop::getItemsFromBasket();
if (empty($items)) {
    echo "<p style='color: red;'>Ваша корзина пуста. Добавьте товары в корзину, прежде чем оформлять заказ.</p>";
    exit();
}
?>

<form action="save_order" method="post">
    <div>
        <label>Заказчик:</label>
        <input type="text" name="customer" size="50" required />
    </div>
    <div>
        <label>Email заказчика:</label>
        <input type="email" name="email" size="50" required />
    </div>
    <div>
        <label>Телефон для связи:</label>
        <input type="tel" name="phone" size="50" required />
    </div>
    <div>
        <label>Адрес доставки:</label>
        <input type="text" name="address" size="50" required />
    </div>
    <input type="hidden" name="items" id="items" value="" />
    <div>
        <input type="submit" value="Заказать" />
    </div>
</form>

<script>
const items = <?php echo json_encode(Eshop::getItemsFromBasket()); ?>;
document.getElementById('items').value = JSON.stringify(items);
</script>