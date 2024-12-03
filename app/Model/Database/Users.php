<?php

namespace App\Model\Database;

use App\Model\Connection;

class Users extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers(): ?array
    {
        $query = "SELECT * FROM users";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function findUserByEmail(string $email): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch();

            return $result ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar usuário por e-mail: " . $e->getMessage());
        }
    }

    public function findUserById($id): ?array 
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);  // Certifique-se de usar \PDO
        $stmt->execute();
        return $stmt->fetch();
    }

    public function insertUser($name, $email, $age): bool
    {
        $query = "INSERT INTO users (name, email, age) VALUES (:name, :email, :age)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, \PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao inserir usuário: " . $e->getMessage());
        }
    }
}
