<?php

class DetalleCaja
{
    private $db;
    private $table = "detallecaja";

    public $id;
    public $caja_id;
    public $transaccion_id;
    public $descripcion;
    public $monto;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createDetalleCaja()
    {
        try {
            $query = "INSERT INTO " . $this->table . " (caja_id, transaccion_id, descripcion, monto) 
                      VALUES (:caja_id, :transaccion_id, :descripcion, :monto)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":caja_id", $this->caja_id, PDO::PARAM_STR);
            $stmt->bindParam(":transaccion_id", $this->transaccion_id, PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":monto", $this->monto, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateDetalleCaja()
    {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET caja_id = :caja_id, transaccion_id = :transaccion_id, descripcion = :descripcion, monto = :monto 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":caja_id", $this->caja_id, PDO::PARAM_STR);
            $stmt->bindParam(":transaccion_id", $this->transaccion_id, PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":monto", $this->monto, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteCaja()
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
