<?php

require_once __DIR__ . '/../Config/dataBase.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class TransaccionController
{
    private $db;
    private $transaccion;

    public function __construct()
    {
        $dataBase = new dataBase();
        $this->db = $dataBase->getConnection();
        $this->transaccion = new Transaccion($this->db);
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt = $this->transaccion->getAll();
            $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "data"   => $transacciones
            ]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function getById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $transaccion = $this->transaccion->getById($id);
            if ($transaccion) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "data"   => $transaccion
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Transacción no encontrada"]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            // Se corrige el error tipográfico: $dat -> $data
            if (
                !isset($data['tipo']) ||
                !isset($data['producto_id']) ||
                !isset($data['cantidad']) ||
                !isset($data['precio_unitario']) ||
                !isset($data['fecha']) ||
                !isset($data['usuario_id'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->transaccion->tipo = $data['tipo'];
            $this->transaccion->producto_id = $data['producto_id'];
            $this->transaccion->cantidad = $data['cantidad'];
            $this->transaccion->precio_unitario = $data['precio_unitario'];
            $this->transaccion->fecha = $data['fecha'];
            $this->transaccion->usuario_id = $data['usuario_id'];

            if ($this->transaccion->createTransaccion()) {
                http_response_code(201);
                echo json_encode([
                    "status"  => 201,
                    "message" => "Transacción creada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al crear la transacción"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (
                !isset($data['tipo']) ||
                !isset($data['producto_id']) ||
                !isset($data['cantidad']) ||
                !isset($data['precio_unitario']) ||
                !isset($data['fecha']) ||
                !isset($data['usuario_id'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->transaccion->id = $id;
            $this->transaccion->tipo = $data['tipo'];
            $this->transaccion->producto_id = $data['producto_id'];
            $this->transaccion->cantidad = $data['cantidad'];
            $this->transaccion->precio_unitario = $data['precio_unitario'];
            $this->transaccion->fecha = $data['fecha'];
            $this->transaccion->usuario_id = $data['usuario_id'];

            if ($this->transaccion->updateTransaccion()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Transacción actualizada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar la transacción"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function patch($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $data = json_decode(file_get_contents("php://input"), true);

            $this->transaccion->id = $id;

            if (isset($data['tipo'])) {
                $this->transaccion->tipo = $data['tipo'];
            }
            if (isset($data['producto_id'])) {
                $this->transaccion->producto_id = $data['producto_id'];
            }
            if (isset($data['cantidad'])) {
                $this->transaccion->cantidad = $data['cantidad'];
            }
            if (isset($data['precio_unitario'])) {
                $this->transaccion->precio_unitario = $data['precio_unitario'];
            }
            if (isset($data['fecha'])) {
                $this->transaccion->fecha = $data['fecha'];
            }
            if (isset($data['usuario_id'])) {
                $this->transaccion->usuario_id = $data['usuario_id'];
            }

            if ($this->transaccion->updateTransaccion()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Transacción actualizada parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar la transacción"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->transaccion->id = $id;
            if ($this->transaccion->deleteTransaccion()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Transacción eliminada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al eliminar la transacción"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
