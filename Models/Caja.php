<?php
class Caja
{
    private $db;
    private $table = "cajadiaria";

    public $id;
    public $fecha;
    public $saldo_inicial;
    public $saldo_final;
    public $abierta_por_id;
    public $cerrada_por_id;
    public $observaciones;

    public function __construct($db){
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCaja() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (fecha, saldo_inicial, saldo_final, abierta_por_id, cerrada_por_id, observaciones) 
                      VALUES (:fecha, :saldo_inicial, :saldo_final, :abierta_por_id, :cerrada_por_id, :observaciones)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":fecha", $this->fecha, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_inicial", $this->saldo_inicial, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_final", $this->saldo_final, PDO::PARAM_STR);
            $stmt->bindParam(":abierta_por_id", $this->abierta_por_id, PDO::PARAM_INT);
            $stmt->bindParam(":cerrada_por_id", $this->cerrada_por_id, PDO::PARAM_INT);
            $stmt->bindParam(":observaciones", $this->observaciones, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateCaja() {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET fecha = :fecha, saldo_inicial = :saldo_inicial, saldo_final = :saldo_final, 
                          abierta_por_id = :abierta_por_id, cerrada_por_id = :cerrada_por_id, observaciones = :observaciones 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":fecha", $this->fecha, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_inicial", $this->saldo_inicial, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_final", $this->saldo_final, PDO::PARAM_STR);
            $stmt->bindParam(":abierta_por_id", $this->abierta_por_id, PDO::PARAM_INT);
            $stmt->bindParam(":cerrada_por_id", $this->cerrada_por_id, PDO::PARAM_INT);
            $stmt->bindParam(":observaciones", $this->observaciones, PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteCaja() {
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
?>
