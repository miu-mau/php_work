<h1>Каталог товаров</h1>
<p class='admin'><a href='admin'>админка</a></p>
<p>Товаров в <a href='basket'>корзине</a>: <?php echo Eshop::getCountItemsInBasket(); ?></p>
<table>
<tr>
    <th>Название</th>
    <th>Автор</th>
    <th>Год издания</th>
    <th>Цена, руб.</th>
    <th>В корзину</th>
</tr>

<?php
$items = Eshop::getItemsFromCatalog();
foreach ($items as $book) {
    echo "<tr>
            <td>{$book->getTitle()}</td>
            <td>{$book->getAuthor()}</td>
            <td>{$book->getPubyear()}</td>
            <td>{$book->getPrice()}</td>
            <td><a href ='add_item_to_basket?title={$book->getTitle()}'>Добавить</a></td>
          </tr>";
}
?>
</table>