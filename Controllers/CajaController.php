<?php
require_once __DIR__ . '/../Config/dataBase.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class CajaController
{
    private $db;
    private $caja;

    public function __construct()
    {
        $dataBase = new dataBase();
        $this->db = $dataBase->getConnection();
        $this->caja = new Caja($this->db);
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt = $this->caja->getAll();
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
            $caja = $this->caja->getById($id);
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
                !isset($data['fecha']) ||
                !isset($data['saldo_inicial']) ||
                !isset($data['saldo_final']) ||
                !isset($data['abierta_por_id']) ||
                !isset($data['cerrada_por_id']) ||
                !isset($data['observaciones'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->caja->fecha = $data['fecha'];
            $this->caja->saldo_inicial = $data['saldo_inicial'];
            $this->caja->saldo_final = $data['saldo_final'];
            $this->caja->abierta_por_id = $data['abierta_por_id'];
            $this->caja->cerrada_por_id = $data['cerrada_por_id'];
            $this->caja->observaciones = $data['observaciones'];

            if ($this->caja->createCaja()) {
                http_response_code(201);
                echo json_encode([
                    "status" => 201,
                    "message" => "Caja creada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al crear la caja"
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
                !isset($data['fecha']) ||
                !isset($data['saldo_inicial']) ||
                !isset($data['saldo_final']) ||
                !isset($data['abierta_por_id']) ||
                !isset($data['cerrada_por_id']) ||
                !isset($data['observaciones'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->caja->id = $id;
            $this->caja->fecha = $data['fecha'];
            $this->caja->saldo_inicial = $data['saldo_inicial'];
            $this->caja->saldo_final = $data['saldo_final'];
            $this->caja->abierta_por_id = $data['abierta_por_id'];
            $this->caja->cerrada_por_id = $data['cerrada_por_id'];
            $this->caja->observaciones = $data['observaciones'];

            if ($this->caja->updateCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Caja actualizada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al actualizar la caja"
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

            $this->caja->id = $id;

            if (isset($data['fecha'])) {
                $this->caja->fecha = $data['fecha'];
            }
            if (isset($data['saldo_inicial'])) {
                $this->caja->saldo_inicial = $data['saldo_inicial'];
            }
            if (isset($data['saldo_final'])) {
                $this->caja->saldo_final = $data['saldo_final'];
            }
            if (isset($data['abierta_por_id'])) {
                $this->caja->abierta_por_id = $data['abierta_por_id'];
            }
            if (isset($data['cerrada_por_id'])) {
                $this->caja->cerrada_por_id = $data['cerrada_por_id'];
            }
            if (isset($data['observaciones'])) {
                $this->caja->observaciones = $data['observaciones'];
            }

            if ($this->caja->updateCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Caja actualizada parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
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
            $this->caja->id = $id;
            if ($this->caja->deleteCaja()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Caja eliminada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al eliminar la caja"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
