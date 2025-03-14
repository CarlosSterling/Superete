<?php

class Usuario {
    private $db;
    private $table = 'usuario';

    public $id;
    public $cedula;
    public $username;
    public $password;
    public $roles;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUsuario() {
        try {
            $query = "INSERT INTO $this->table (cedula, username, password, roles) VALUES (:cedula, :username, :password, :roles)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":cedula", $this->cedula, PDO::PARAM_INT);
            $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
            $stmt->bindParam(":roles", $this->roles, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateUsuario() {
        try {
            $query = "UPDATE $this->table SET cedula = :cedula, username = :username, password = :password, roles = :roles WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":cedula", $this->cedula, PDO::PARAM_INT);
            $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
            $stmt->bindParam(":roles", $this->roles, PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateUsuarioPartial($fields) {
        try {
            $setClause = [];
            foreach ($fields as $key => $value) {
                $setClause[] = "$key = :$key";
            }
            $setClauseStr = implode(", ", $setClause);
            $query = "UPDATE $this->table SET $setClauseStr WHERE id = :id";
            $stmt = $this->db->prepare($query);

            foreach ($fields as $key => $value) {
                // Asumimos que 'cedula' es numérico y el resto cadenas
                if ($key == "cedula") {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
                }
            }
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteUsuario() {
        try {
            $query = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
