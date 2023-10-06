<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createUser($firstname, $lastname, $email, $hashed_password) {
        $query = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $firstname);
        $stmt->bindParam(2, $lastname);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $hashed_password);
        
        try {
            $stmt->execute();
            return true;  // Return true if the user was successfully created
        } catch (PDOException $e) {
            // You might want to check for error code 23000 here, which is the SQLSTATE for a unique constraint violation
            if ($e->getCode() == 23000) {
                return false;  // Return false if the user could not be created due to a duplicate email
            }
            throw $e;  // Otherwise, rethrow the exception
        }
    }
    

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

