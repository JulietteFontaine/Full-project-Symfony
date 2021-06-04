<?php

namespace App\Persistence;

use PDO;

class CustomersOperations

{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save(string $firstName, string $lastName, string $email)
    {
        $sql = "INSERT INTO customer SET first_name = :fn, last_name = :ln, email = :email";

        $query = $this->db->prepare($sql);

        $query->execute([
            'fn' => $firstName,
            'ln' => $lastName,
            'email' => $email,
        ]);
    }
}
