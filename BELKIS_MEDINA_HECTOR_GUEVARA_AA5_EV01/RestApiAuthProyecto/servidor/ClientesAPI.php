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
        //utilizamos debug.logs un archivo de registro de depuración 
       // que se usa para guardar información importante sobre el funcionamiento del código .
    
        file_put_contents("debug.log", "🔹 Método recibido: " . $_SERVER["REQUEST_METHOD"] . "\n", FILE_APPEND);
        file_put_contents("debug.log", "🔹 Parámetros GET: " . print_r($_GET, true) . "\n", FILE_APPEND);
    
        $method = $_SERVER["REQUEST_METHOD"];
        file_put_contents("debug.log", "🔹 Contenido de \$_SERVER[\"REQUEST_URI\"]: " . $_SERVER["REQUEST_URI"] . "\n", FILE_APPEND);
        file_put_contents("debug.log", "🔹 Contenido de \$_GET: " . print_r($_GET, true) . "\n", FILE_APPEND);
        
        file_put_contents("debug.log", "🔹 Método recibido: " . $_SERVER["REQUEST_METHOD"] . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Parámetros GET en API: " . print_r($_GET, true) . "\n", FILE_APPEND);

file_put_contents("debug.log", "🔹 Método recibido en API: " . $_SERVER["REQUEST_METHOD"] . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Parámetros GET en API: " . print_r($_GET, true) . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Parámetros POST en API: " . file_get_contents("php://input") . "\n", FILE_APPEND);

file_put_contents("debug.log", "🔹 Método recibido en API: " . $_SERVER["REQUEST_METHOD"] . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Contenido de \$_SERVER[\"REQUEST_URI\"]: " . $_SERVER["REQUEST_URI"] . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Contenido de \$_GET en API: " . print_r($_GET, true) . "\n", FILE_APPEND);
file_put_contents("debug.log", "🔹 Contenido de \$_POST en API: " . file_get_contents("php://input") . "\n", FILE_APPEND);

//se definen los metodos
        switch ($method) {
            case "POST":
                if (isset($_GET["action"])) {
                    file_put_contents("debug.log", "🔹 Acción recibida: " . $_GET["action"] . "\n", FILE_APPEND);
    
                    if ($_GET["action"] == "register") {
                        $this->registrarUsuario();
                        return;
                    } elseif ($_GET["action"] == "login") {
                        $this->iniciarSesion();
                        return;
                    }
                }
                break;
            case "DELETE":
                if (isset($_GET["action"]) && $_GET["action"] == "logout") {
                    $this->cerrarSesion();
                    return;
                }
                break;
            default:
                $this->response(405, "error", "Método no permitido");
                return;
        }
        file_put_contents("debug.log", "🔹 JSON generado antes de enviar: " . $json . "\n", FILE_APPEND);
        file_put_contents("debug.log", "🔹 JSON generado antes de enviar: " . json_encode(["status" => "success", "message" => ["token" => "test123"]]) . "\n", FILE_APPEND);

        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        file_put_contents("debug.log", "🔹 JSON enviado por la API: " . json_encode(["status" => "success", "message" => ["token" => "test123"]]) . "\n", FILE_APPEND);
exit;
        return;
    }
    //se definen las funciones privadas
    private function registrarUsuario() {

        try {
            // Leer y decodificar datos del cuerpo de la petición
            $datos = json_decode(file_get_contents("php://input"), true);
    
            // DEPURACIÓN: Verificar si los datos llegan correctamente
            file_put_contents("debug.log", print_r($datos, true));
    
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
    
        file_put_contents("debug.log", "🟢 Entrando a iniciarSesion() en ClientesAPI.php\n", FILE_APPEND);
    
        $datos = json_decode(file_get_contents("php://input"), true);
        file_put_contents("debug.log", "📥 Datos recibidos: " . print_r($datos, true) . "\n", FILE_APPEND);
    
        if (!$datos || !isset($datos["Correo_electronico"], $datos["Contrasena"])) {
            file_put_contents("debug.log", "❌ Error: Datos incompletos en iniciarSesion().\n", FILE_APPEND);
            $json = json_encode(["status" => "error", "message" => "Datos incompletos"]);
            file_put_contents("debug.log", "🔹 JSON enviado por la API (error): " . $json . "\n", FILE_APPEND);
            echo $json;
            exit;
        }
    
        file_put_contents("debug.log", "🔍 Buscando usuario en la base de datos...\n", FILE_APPEND);
    
        $id_cliente = $this->db->iniciarSesion($datos["Correo_electronico"], $datos["Contrasena"]);
        
        if ($id_cliente) {
            file_put_contents("debug.log", "✅ Inicio de sesión exitoso para: " . $datos["Correo_electronico"] . "\n", FILE_APPEND);
            $token = bin2hex(random_bytes(32));
            $this->db->guardarSesion($id_cliente, $token);
    
            $respuesta = ["status" => "success", "message" => ["token" => $token]];
            $json = json_encode($respuesta);
    
            file_put_contents("debug.log", "🔹 Respuesta enviada por la API: " . $json . "\n", FILE_APPEND);
            echo $json;
            exit;
        } else {
            file_put_contents("debug.log", "❌ Error: Credenciales incorrectas para: " . $datos["Correo_electronico"] . "\n", FILE_APPEND);
            $json = json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
            file_put_contents("debug.log", "🔹 JSON enviado por la API (error): " . $json . "\n", FILE_APPEND);
            echo $json;
            exit;
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
