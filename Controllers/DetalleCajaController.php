<?php

require_once __DIR__ . '/../Config/dataBase.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class DetalleCajaController
{
    private $db;
    private $detalleCaja;

    public function __construct()
    {
        $dataBase = new dataBase();
        $this->db = $dataBase->getConnection();
        $this->detalleCaja = new DetalleCaja($this->db);
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt = $this->detalleCaja->getAll();
            $caja = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "data" => $caja
            ]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function getById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $caja = $this->detalleCaja->getById($id);
            if ($caja) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "data" => $caja
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Caja no encontrada"]);
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

            if (
                !isset($data['caja_id']) ||
                !isset($data['transaccion_id']) ||
                !isset($data['descripcion']) ||
                !isset($data['monto'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->detalleCaja->caja_id        = $data['caja_id'];
            $this->detalleCaja->transaccion_id   = $data['transaccion_id'];
            $this->detalleCaja->descripcion      = $data['descripcion'];
            $this->detalleCaja->monto            = $data['monto'];

            if ($this->detalleCaja->createDetalleCaja()) {
                http_response_code(201);
                echo json_encode([
                    "status"  => 201,
                    "message" => "DetalleCaja creado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al crear detalle de caja"
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
                !isset($data['caja_id']) ||
                !isset($data['transaccion_id']) ||
                !isset($data['descripcion']) ||
                !isset($data['monto'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->detalleCaja->id             = $id;
            $this->detalleCaja->caja_id        = $data['caja_id'];
            $this->detalleCaja->transaccion_id   = $data['transaccion_id'];
            $this->detalleCaja->descripcion      = $data['descripcion'];
            $this->detalleCaja->monto            = $data['monto'];

            if ($this->detalleCaja->updateDetalleCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Detalle caja actualizado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar detalle caja"
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

            $this->detalleCaja->id = $id;

            if (isset($data['caja_id'])) {
                $this->detalleCaja->caja_id = $data['caja_id'];
            }
            if (isset($data['transaccion_id'])) {
                $this->detalleCaja->transaccion_id = $data['transaccion_id'];
            }
            if (isset($data['descripcion'])) {
                $this->detalleCaja->descripcion = $data['descripcion'];
            }
            if (isset($data['monto'])) {
                $this->detalleCaja->monto = $data['monto'];
            }

            if ($this->detalleCaja->updateDetalleCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Detalle caja actualizada parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar la caja"
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
            $this->detalleCaja->id = $id;
            if ($this->detalleCaja->deleteCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Detalle caja eliminada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al eliminar detalle caja"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
