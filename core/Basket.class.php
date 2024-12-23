<?php
class Basket
{
    private $items = [];

    public function init()
    {
        if (isset($_COOKIE['basket'])) {
            $this->items = json_decode($_COOKIE['basket'], true);
        } else {
            $this->items = [];
        }
    }

    public function add($book)
    {
        $found = false;
        foreach ($this->items as &$item) {
            if ($item['title'] === $book['title']) {
                $item['quantity'] += 1; 
                $found = true;
                break;
            }
        }

        if (!$found) {
            $book['quantity'] = 1; 
            $this->items[] = $book;
        }

        $this->save();
    }

    public function remove($title)
    {
        foreach ($this->items as $key => &$item) {
            if ($item['title'] === $title) {
                $item['quantity'] -= 1; 
                if ($item['quantity'] < 1) {
                    
                    unset($this->items[$key]);
                }
                $this->save();
                return;
            }
        }
    }

    public function clear()
    {
        $this->items = []; 
        $this->save(); 
    }
    
    public function save()
    {
        setcookie('basket', json_encode($this->items), time() + 3600, '/'); 
    }

    public function getItems()
    {
        return $this->items;
    }

    public function countItems()
    {
        $totalCount = 0;
        foreach ($this->items as $item) {
            $totalCount += $item['quantity'];
        }
        return $totalCount;
    }

    public function getTotalPrice()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
?>