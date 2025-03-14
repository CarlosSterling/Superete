<?php
class Categoria
{
    private $connect;
    private $table = "categoria";

    public $id;
    public $nombre;

    public function __construct($db)
    {
        $this->connect = $db;
    }

    // Obtener todas las categorías
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        try {
            $query = "INSERT INTO " . $this->table . " (nombre) VALUES (:nombre)";
            $stmt = $this->connect->prepare($query);
            // Especificar el tipo de dato puede ayudar a prevenir problemas
            $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            // Opcional: registrar error para depuración
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " SET nombre = :nombre WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function patch($data = [])
    {
        $fields = [];
        if (isset($data['nombre'])) {
            $fields['nombre'] = $data['nombre'];
        }
        if (empty($fields)) {
            return false;
        }
        $setClauses = [];
        foreach ($fields as $field => $value) {
            $setClauses[] = "$field = :$field";
        }
        $sql = "UPDATE " . $this->table . " SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        foreach ($fields as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        return $stmt->execute();
    }
}
