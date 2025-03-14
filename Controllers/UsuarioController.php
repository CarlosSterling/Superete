<?php

require_once __DIR__ .'/../Config/database.php';
require_once __DIR__ . '/../Autoload/Autoload.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function getAll() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $stmt = $this->usuario->getAll();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "status" => 200,
                "data" => $usuarios
            ]);
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => 405,
                "message" => "Método no permitido"
            ]);
        }
    }

    public function getById($id) {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $usuario = $this->usuario->getById($id);
            if ($usuario) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "data" => $usuario
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['cedula']) || !isset($data['username']) || !isset($data['password']) || !isset($data['roles'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->usuario->cedula = $data['cedula'];
            $this->usuario->username = $data['username'];
            #Aquí se puede aplicar un hash a la contraseña para mayor seguridad
            $this->usuario->password = $data['password'];
            $this->usuario->roles = $data['roles'];

            if ($this->usuario->createUsuario()) {
                http_response_code(201);
                echo json_encode([
                    "status" => 201,
                    "message" => "Usuario creado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al crear el usuario"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function update($id) {
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['cedula']) || !isset($data['username']) || !isset($data['password']) || !isset($data['roles'])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Faltan datos necesarios"
                ]);
                return;
            }

            $this->usuario->id = $id;
            $this->usuario->cedula = $data['cedula'];
            $this->usuario->username = $data['username'];
            $this->usuario->password = $data['password'];
            $this->usuario->roles = $data['roles'];

            if ($this->usuario->updateUsuario()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Usuario actualizado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al actualizar el usuario"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function patch($id) {
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->usuario->id = $id;
            $fields = [];
            if (isset($data['cedula'])) {
                $fields['cedula'] = $data['cedula'];
            }
            if (isset($data['username'])) {
                $fields['username'] = $data['username'];
            }
            if (isset($data['password'])) {
                $fields['password'] = $data['password'];
            }
            if (isset($data['roles'])) {
                $fields['roles'] = $data['roles'];
            }

            if (empty($fields)) {
                http_response_code(400);
                echo json_encode([
                    "status" => "Error",
                    "message" => "No se proporcionaron campos para actualizar"
                ]);
                return;
            }

            if ($this->usuario->updateUsuarioPartial($fields)) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Usuario actualizado parcialmente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al actualizar el usuario"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    public function delete($id) {
        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            $this->usuario->id = $id;
            if ($this->usuario->deleteUsuario()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 200,
                    "message" => "Usuario eliminado"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "Error",
                    "message" => "Error al eliminar el usuario"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
