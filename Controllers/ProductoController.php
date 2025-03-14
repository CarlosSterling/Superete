<?php

require_once __DIR__ . '/../Config/dataBase.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class ProductoController
{
    private $db;
    private $producto;

    public function __construct()
    {
        $dataBase = new dataBase();
        $this->db = $dataBase->getConnection();
        $this->producto = new Producto($this->db);
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt = $this->producto->getAll();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "data"   => $productos
            ]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function getById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $producto = $this->producto->getById($id);
            if ($producto) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "data"   => $producto
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado"]);
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
                !isset($data['nombre']) ||
                !isset($data['categoria_id']) ||
                !isset($data['precio_compra']) ||
                !isset($data['precio_venta']) ||
                !isset($data['stock'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->producto->nombre        = $data['nombre'];
            $this->producto->categoria_id  = $data['categoria_id'];
            $this->producto->precio_compra = $data['precio_compra'];
            $this->producto->precio_venta  = $data['precio_venta'];
            $this->producto->stock         = $data['stock'];

            if ($this->producto->createProducto()) {
                http_response_code(201);
                echo json_encode([
                    "status"  => 201,
                    "message" => "Producto creado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al crear producto"
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
                !isset($data['nombre']) ||
                !isset($data['categoria_id']) ||
                !isset($data['precio_compra']) ||
                !isset($data['precio_venta']) ||
                !isset($data['stock'])
            ) {
                http_response_code(400);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->producto->id            = $id;
            $this->producto->nombre        = $data['nombre'];
            $this->producto->categoria_id  = $data['categoria_id'];
            $this->producto->precio_compra = $data['precio_compra'];
            $this->producto->precio_venta  = $data['precio_venta'];
            $this->producto->stock         = $data['stock'];

            if ($this->producto->updateProducto()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Producto actualizado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar producto"
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

            $this->producto->id = $id;

            if (isset($data['nombre'])) {
                $this->producto->nombre = $data['nombre'];
            }
            if (isset($data['categoria_id'])) {
                $this->producto->categoria_id = $data['categoria_id'];
            }
            if (isset($data['precio_compra'])) {
                $this->producto->precio_compra = $data['precio_compra'];
            }
            if (isset($data['precio_venta'])) {
                $this->producto->precio_venta = $data['precio_venta'];
            }
            if (isset($data['stock'])) {
                $this->producto->stock = $data['stock'];
            }

            if ($this->producto->updateProducto()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Producto actualizado parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al actualizar producto"
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
            $this->producto->id = $id;
            if ($this->producto->deleteProducto()) {
                http_response_code(200);
                echo json_encode([
                    "status"  => 200,
                    "message" => "Producto eliminado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status"  => "Error",
                    "message" => "Error al eliminar producto"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
