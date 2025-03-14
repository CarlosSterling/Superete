<?php
require_once __DIR__ . '/../Config/dataBase.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class CategoriaController
{
    private $db;
    private $categoria;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoria = new Categoria($this->db);
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt = $this->categoria->getAll();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "data" => $categorias
            ]);
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "Error",
                "message" => "Método no permitido"
            ]);
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['nombre'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Datos incompletos"
                ]);
                return;
            }
            $this->categoria->nombre = $data['nombre'];

            if ($this->categoria->create()) {
                http_response_code(201);
                echo json_encode([
                    "status" => 201,
                    "message" => "Categoría creada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al crear categoría"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "Error",
                "message" => "Método no permitido"
            ]);
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['nombre'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Datos incompletos"
                ]);
                return;
            }
            $this->categoria->id = $id;
            $this->categoria->nombre = $data['nombre'];

            if ($this->categoria->update()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Categoría actualizada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al actualizar categoría"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "Error",
                "message" => "Método no permitido"
            ]);
        }
    }

    public function patch($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->categoria->id = $id;
            if ($this->categoria->patch($data)) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Categoría actualizada parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "No se pudo actualizar la categoría (o no se enviaron campos)"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "Error",
                "message" => "Método no permitido"
            ]);
        }
    }


    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            if ($this->categoria->delete($id)) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Categoría eliminada correctamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "No se pudo eliminar la categoría"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "Error",
                "message" => "Método no permitido"
            ]);
        }
    }
}
