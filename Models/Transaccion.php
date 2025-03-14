<?php

class Transaccion
{
    private $db;
    private $table = "transaccion";

    public $id;
    public $tipo;
    public $producto_id;
    public $cantidad;
    public $precio_unitario;
    public $fecha;
    public $usuario_id;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTransaccion()
    {
        try {
            $query = "INSERT INTO " . $this->table . " (tipo, producto_id, cantidad, precio_unitario, fecha, usuario_id) 
                      VALUES (:tipo, :producto_id, :cantidad, :precio_unitario, :fecha, :usuario_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(":precio_unitario", $this->precio_unitario, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $this->fecha, PDO::PARAM_STR);
            $stmt->bindParam(":usuario_id", $this->usuario_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateTransaccion()
    {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET tipo = :tipo, producto_id = :producto_id, cantidad = :cantidad, precio_unitario = :precio_unitario, fecha = :fecha, usuario_id = :usuario_id 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(":precio_unitario", $this->precio_unitario, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $this->fecha, PDO::PARAM_STR);
            $stmt->bindParam(":usuario_id", $this->usuario_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteTransaccion()
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt  = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
