<?php
class Book
{
    private $title;
    private $author;
    private $price;
    private $pubyear;
    private $id_catalog; 
    
    public function __construct($title, $author, $price, $pubyear, $id_catalog = null)
    {
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->pubyear = $pubyear;
        $this->id_catalog = $id_catalog; 
    }

    public function getIdCatalog()
    {
        return $this->id_catalog; 
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getPubyear()
    {
        return $this->pubyear;
    }
    public static function findByTitle($title)
    {
        $conn = Eshop::init(DB);
        $sql = 'CALL spGetItemsForBasket(:title)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new self($row['title'], $row['author'], $row['price'], $row['pubyear'], $row['id_catalog']); 
        }
        return null;
    }
}
?>
