<?php
class User {
    private $username;
    private $password;
    private $email;

    public function __construct($username, $password, $email) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPasswordHash() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }
    public function toArray()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email
        ];
    }
}
?>