<?php

class Producto
{
    private $db;
    private $table = "producto";

    public $id;
    public $nombre;
    public $categoria_id;
    public $precio_compra;
    public $precio_venta;
    public $stock;

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

    public function createProducto()
    {
        try {
            $query = "INSERT INTO " . $this->table . " (nombre, categoria_id, precio_compra, precio_venta, stock) 
                      VALUES (:nombre, :categoria_id, :precio_compra, :precio_venta, :stock)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria_id", $this->categoria_id, PDO::PARAM_STR);
            $stmt->bindParam(":precio_compra", $this->precio_compra, PDO::PARAM_STR);
            $stmt->bindParam(":precio_venta", $this->precio_venta, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $this->stock, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateProducto()
    {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, categoria_id = :categoria_id, precio_compra = :precio_compra, precio_venta = :precio_venta, stock = :stock 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria_id", $this->categoria_id, PDO::PARAM_STR);
            $stmt->bindParam(":precio_compra", $this->precio_compra, PDO::PARAM_STR);
            $stmt->bindParam(":precio_venta", $this->precio_venta, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $this->stock, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteProducto()
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
