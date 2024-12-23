<?php
class Order
{
    private $customer;
    private $email;
    private $phone;
    private $address;
    private $id;
    private $created;
    private $items;

    public function __construct($customer, $email, $phone, $address, $items = [])
    {
        $this->customer = $customer;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->items = $items;
        $this->created = date('Y-m-d H:i:s');
    }


    public function getCustomer()
    {
        return $this->customer;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getItems()
    {
        return $this->items;
    }
}