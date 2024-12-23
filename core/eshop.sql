CREATE TABLE `catalog`(
    `id_catalog` INT AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `author` VARCHAR(255) NOT NULL,
    `price` INT NOT NULL,
    `pubyear` YEAR,
    PRIMARY KEY (`id_catalog`)
);
CREATE PROCEDURE spAddItemToCatalog(IN title VARCHAR(255), IN author VARCHAR(255), IN price INT, IN pubyear YEAR)
BEGIN
    INSERT INTO `catalog` (`title`, `author`, `price`, `pubyear`)
    VALUES (title, author, price, pubyear);
END 

CALL spAddItemToCatalog('Harry Potter and the Sorcerers Stone', 'J.K. Rowling', 1000, '1997');
CALL spAddItemToCatalog('Flowers for Algernon', 'Daniel Keyes', 508, '1966');
CALL spAddItemToCatalog('The Hunger Games', 'Suzanne Collins', 800, '2008');

CREATE PROCEDURE spGetCatalog()
BEGIN
 SELECT * FROM `catalog`;
END
CALL spGetCatalog();

CREATE PROCEDURE spGetItemsForBasket(IN title_p VARCHAR(255))
BEGIN
    SELECT * FROM `catalog` 
    WHERE `title` = title_p;
END
CALL spGetItemsForBasket('Harry Potter and the Sorcerers Stone');






CREATE TABLE `orders` (
    `id_orders` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNIQUE NOT NULL,
    `customer` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE PROCEDURE spSaveOrder(IN customer VARCHAR(255), IN email VARCHAR(255), IN phone VARCHAR(50), IN address VARCHAR(255))
BEGIN
    DECLARE random_order_id INT;
    DECLARE is_unique INT DEFAULT 0;

    WHILE is_unique = 0 DO
        SET random_order_id = FLOOR(100 + RAND() * 900); 
        IF (SELECT COUNT(*) FROM orders WHERE order_id = random_order_id) = 0 THEN
            SET is_unique = 1;
        END IF;
    END WHILE;

    INSERT INTO `orders` (`order_id`, `customer`, `email`, `phone`, `address`)
    VALUES (random_order_id, customer, email, phone, address);

    SELECT random_order_id AS order_id;
END;
CALL spSaveOrder('Elizabeth', 'test@mail.ru', '890569583', 'Moscow, Tverskaya str. 1');

CREATE PROCEDURE spGetOrders()
BEGIN
    SELECT * FROM `orders`;
END
CALL spGetOrders();





CREATE TABLE `ordered_items` (
    `id_item` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL REFERENCES `orders`(`order_id`),
    `catalog_id` INT NOT NULL REFERENCES `catalog`(`id_catalog`),
    `quantity` INT NOT NULL
);
CREATE PROCEDURE spSaveOrderedItems(IN orderId INT, IN catalogId INT, IN quantity INT)
BEGIN
    INSERT INTO `ordered_items` (`order_id`, `catalog_id`, `quantity`)
    VALUES (orderId, catalogId, quantity);
END
CALL spSaveOrderedItems(383, 2, 1);
CALL spSaveOrderedItems(383, 1, 1);
-- SELECT * FROM ordered_items;






CREATE TABLE `admin` (
    `id_admin` INT AUTO_INCREMENT PRIMARY KEY,
    `login` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP   
);

CREATE PROCEDURE spSaveAdmin(IN login VARCHAR(255), IN password VARCHAR(255), IN email VARCHAR(255))
BEGIN
    INSERT INTO `admin` (`login`, `password`, `email`)
    VALUES (login, password, email);
END
CALL spSaveAdmin('admin', '123', '');

-- работает
CREATE PROCEDURE spGetAdmin(IN login_p VARCHAR(255))
BEGIN
    SELECT * FROM `admin`
    WHERE `login` = login_p;
END

CALL spGetAdmin('admin');
-- SELECT * FROM `admin`;


CREATE PROCEDURE spGetOrderedItems(IN orderId INT)
BEGIN
    SELECT
        orders.`order_id` AS "Номер заказа",
        catalog.`title` AS "Название книги",
        catalog.`author` AS "Автор",
        catalog.pubyear AS "Год издания",
        catalog.`price` AS "Цена",
        ordered_items.`quantity` AS "Количество"
    FROM `ordered_items`
    INNER JOIN catalog ON 
        ordered_items.`catalog_id` = catalog.`id_catalog`
    INNER JOIN `orders` ON 
        ordered_items.`order_id` = orders.`order_id`
    WHERE orders.`order_id` = orderId; 
END;
CALL spGetOrderedItems(383);



-- удаление таблиц и процедур

drop procedure spGetOrderedItems;
drop procedure spGetAdmin;
drop procedure spSaveAdmin;
drop table `admin`;

drop procedure spSaveOrderedItems;
drop table `ordered_items`;

drop procedure spGetOrders;
drop procedure spSaveOrder;
drop table `orders`;

drop procedure spGetItemsForBasket;
drop procedure spGetCatalog;
drop procedure spAddItemToCatalog;
drop table `catalog`;
