<?php
require_once "ClientesDB.php";

//Se define la clase
class ClientesAPI {
    private $db;
//se define la funciones publicas

    public function __construct() {
        $this->db = new ClientesDB();
    }
    public function API() {
        header("Content-Type: application/json");

        $method = $_SERVER["REQUEST_METHOD"];
        switch ($method) {
            case "POST":
                if (isset($_GET["action"])) {
                    if ($_GET["action"] == "register") {
                        $this->registrarUsuario();
                    } elseif ($_GET["action"] == "login") {
                        $this->iniciarSesion();
                    }
                }
                break;
            case "DELETE":
                if (isset($_GET["action"]) && $_GET["action"] == "logout") {
                    $this->cerrarSesion();
                }
                break;
            default:
                $this->response(405, "error", "Método no permitido");
                break;
        }
    }
    //se definen las funciones privadas
    private function registrarUsuario() {

        try {
            // Leer y decodificar datos del cuerpo de la petición
            $datos = json_decode(file_get_contents("php://input"), true);
    
            if (!$datos) {
                throw new Exception("Error al decodificar JSON o JSON vacío");
            }
    
            // Validar que los datos necesarios están presentes
            if (!isset($datos["Nombre"], $datos["Direccion"], $datos["Telefono"],$datos["Correo_electronico"], $datos["Contrasena"])) {
                throw new Exception("Datos incompletos: Se requieren nombre, direccion, telefono, correo_electronico y contrasena");
            }
    
            // Intentar registrar al usuario en la base de datos
            $registroExitoso = $this->db->registrarUsuario($datos["Nombre"], $datos["Direccion"], $datos["Telefono"],$datos["Correo_electronico"], $datos["Contrasena"]);
            if (!$registroExitoso) {
                return $this->response(200, "error", "El usuario ya se encuentra registrado");
            }
    
            // Respuesta exitosa
            return $this->response(201, "success", "Usuario registrado");
    
        } catch (Exception $e) {
            var_dump($e);

            // Manejo de errores y respuesta adecuada
           return $this->response(500, "error", $e->getMessage());
        }
    }
    private function iniciarSesion() {
        header("Content-Type: application/json");
        ob_clean(); // Limpia cualquier salida anterior
    
        $inputJSON = file_get_contents("php://input"); //esto
        $datos = json_decode(file_get_contents("php://input"), true);
    
        if (!$datos || !isset($datos["Correo_electronico"], $datos["Contrasena"])) {
            $json = json_encode(["status" => "error", "message" => "Datos incompletos"]);
            echo $json;
            exit;
            
        }
        $id_cliente = $this->db->iniciarSesion($datos["Correo_electronico"], $datos["Contrasena"]);
        
        if ($id_cliente) {
            $token = bin2hex(random_bytes(32));
            $this->db->guardarSesion($id_cliente, $token);
    
            $respuesta = ["status" => "success", "message" => ["token" => $token]];
            $json = json_encode($respuesta);
            echo $json;
            
        } else {
            $json = json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
            echo $json;
        }
    }
    private function cerrarSesion() {
        $token = $_GET["token"] ?? "";
        if ($token && $this->db->cerrarSesion($token)) {
            $this->response(200, "success", "Sesión cerrada");
        } else {
            $this->response(400, "error", "Error al cerrar sesión");
        }
    }
    private function response($code, $status, $message) {
        http_response_code($code);
        echo json_encode(["status" => $status, "message" => $message], JSON_PRETTY_PRINT);
    }
}
$api = new ClientesAPI();
$api->API();
?>
