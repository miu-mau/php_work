<?php
require_once __DIR__ . '/Book.php';
class Eshop
{
    private static $connection = null;


    public static function init($dbConfig)
    {
        if (self::$connection === null) {
            try {
                $dsn = 'mysql:host=' . $dbConfig['HOST'] . ';dbname=' . $dbConfig['NAME'] . ';charset=utf8';
                self::$connection = new PDO($dsn, $dbConfig['USER'], $dbConfig['PASS']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception('Ошибка подключения к базе данных: ' . $e->getMessage());
            }
        }
    
        return self::$connection;
    }

    public static function addItemToCatalog($book)
    {

        $conn = self::init(DB);


        $sql = 'CALL spAddItemToCatalog(:title, :author, :price, :pubyear)';
        $stmt = $conn->prepare($sql);
    

        $title = $book->getTitle();
        $author = $book->getAuthor();
        $price = $book->getPrice();
        $pubyear = $book->getPubyear();
    

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':pubyear', $pubyear);
    

        return $stmt->execute();
    }
    public static function getItemsFromCatalog()
    {
        $conn = self::init(DB);
        $sql = 'CALL spGetCatalog()';
        $stmt = $conn->query($sql);
        
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book($row['title'], $row['author'], $row['price'], $row['pubyear'], $row['id_catalog']);
        }
        
        return new IteratorIterator(new ArrayIterator($books));
    }
    
    public static function addItemToBasket($book)
    {
        $basket = new Basket();
        $basket->init();
        $basket->add([
            'id_catalog' => $book->getIdCatalog(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'price' => $book->getPrice(),
            'pubyear' => $book->getPubyear(),
            'quantity' => 1 
        ]);
    }

    public static function removeItemFromBasket($title)
    {
        $basket = new Basket();
        $basket->init();
        $basket->remove($title);
    }

    public static function getItemsFromBasket()
    {
        $basket = new Basket();
        $basket->init();
        return $basket->getItems();
    }
    public static function getCountItemsInBasket()
    {
        $basket = new Basket();
        $basket->init();
        return $basket->countItems();
    }
    public static function saveOrder(Order $order)
    {
        $conn = self::init(DB);
        
        $sql = 'CALL spSaveOrder(:customer, :email, :phone, :address)';
        $stmt = $conn->prepare($sql);
        
        $customer = $order->getCustomer();
        $email = $order->getEmail();
        $phone = $order->getPhone();
        $address = $order->getAddress();
    
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
    
        $orderId = $stmt->fetchColumn(); 
    
        if ($orderId === false) {
            throw new Exception("Не удалось получить order_id после сохранения заказа.");
        }
    
        foreach ($order->getItems() as $item) {
            $sql = 'CALL spSaveOrderedItems(:orderId, :catalogId, :quantity)';
            $stmt = $conn->prepare($sql);
            
            $orderIdParam = $orderId;
            $catalogIdParam = $item['id_catalog'];
            $quantityParam = $item['quantity']; 
    
            $stmt->bindParam(':orderId', $orderIdParam);
            $stmt->bindParam(':catalogId', $catalogIdParam);
            $stmt->bindParam(':quantity', $quantityParam);
            $stmt->execute();
        }
    
        $basket = new Basket();
        $basket->init();
        $basket->clear(); 
    }
    public static function getOrders()
    {
        $conn = self::init(DB);
        $sql = 'CALL spGetOrders()';
        $stmt = $conn->query($sql);
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row; 
        }
        
     return $orders;
    }
    
    public static function getOrderedItems($orderId)
    {
        $conn = self::init(DB);
        $sql = 'CALL spGetOrderedItems(:orderId)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row; 
        }
        
        return $items;
    }
    public static function userAdd(User $user)
    {
        $conn = self::init(DB);
        $sql = 'CALL spSaveAdmin(:login, :password, :email)';
        $stmt = $conn->prepare($sql);
        
        $login = $user->getUsername();
        $passwordHash = password_hash($user->getPasswordHash(), PASSWORD_DEFAULT);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $passwordHash); 
    
        
        $email = Cleaner::str($user->getEmail());
        $stmt->bindParam(':email', $email); 
    
        return $stmt->execute(); 
    }
    
    public static function userCheck(User $user): bool
    {
        $conn = self::init(DB);
        $sql = 'CALL spGetAdmin(:login)';
        $stmt = $conn->prepare($sql);
        

        $username = $user->getUsername();
        $stmt->bindParam(':login', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    public static function userGet(User $user): ?User       
    {
        $conn = self::init(DB);
        $sql = 'CALL spGetAdmin(:login)';
        $stmt = $conn->prepare($sql);
        
        $username = $user->getUsername(); 
        $stmt->bindParam(':login', $username); 
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['login'], $row['password'], $row['email']);
        }
        return null;
    }
    
    public static function createHash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public static function isAdmin(): bool
    {
        return isset($_SESSION['admin']);
    }
    
    public static function logIn(User $user): bool
    {
        $existingUser  = self::userGet($user);
        if ($existingUser ) {

            if ($user->getPasswordHash() === $existingUser ->getPasswordHash()) {
                $_SESSION['admin'] = $existingUser ->getUsername();
                return true;
            }
        }
        return false;
    }
    
    public static function logOut()
    {
        unset($_SESSION['admin']);
    }

}
?>
