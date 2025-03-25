<?php
require_once __DIR__ . "/config/database.php";
// se define a clase
class ClientesDB {
    private $conn;

// se define las funciones publicas
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function clienteExiste($correo_electronico) {
        $query = "SELECT Id_Cliente FROM cliente WHERE Correo_electronico= :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    public function registrarUsuario($nombre, $direccion, $telefono, $correo_electronico, $contrasena) {
        try {
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $query = "INSERT INTO cliente (Nombre, Direccion, Telefono, Correo_electronico, Contrasena) VALUES (:Nombre, :Direccion, :Telefono, :Correo_electronico, :Contrasena)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Nombre", $nombre);
            $stmt->bindParam(":Direccion", $direccion);
            $stmt->bindParam(":Telefono", $telefono);
            $stmt->bindParam(":Correo_electronico", $correo_electronico);
            $stmt->bindParam(":Contrasena", $hashed_password);
    
            //DEPURACIÓN: Verificar si la consulta SQL se ejecuta correctamente
            if (!$stmt->execute()) {
                throw new Exception("Error en la consulta SQL: " . implode(" ", $stmt->errorInfo()));
            }
           // utilizamos debug.log un archivo de registro de depuración que se usa para guardar información importante sobre el funcionamiento del código .
            file_put_contents("debug.log", "Hash generado: $hashed_password\n", FILE_APPEND);

            return true;
    
        } catch (Exception $e) {
            file_put_contents("debug.log", "Error en registrarUsuario: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
            
        }
    }
    public function iniciarSesion($correo_electronico, $contrasena) {
        file_put_contents("debug.log", "🟢 Intentando iniciar sesión para: $correo_electronico\n", FILE_APPEND);
    
        $query = "SELECT Id_Cliente, Nombre, Contrasena FROM cliente WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$cliente) {
            file_put_contents("debug.log", "❌ Error: Usuario no encontrado en la base de datos.\n", FILE_APPEND);
            return false;
        }
    
        file_put_contents("debug.log", "✅ Usuario encontrado: " . print_r($cliente, true) . "\n", FILE_APPEND);
    
        if (password_verify($contrasena, $cliente["Contrasena"])) {
            file_put_contents("debug.log", "✅ Inicio de sesión exitoso para usuario: $correo_electronico\n", FILE_APPEND);
            return $cliente["Id_Cliente"];
        }
    
        file_put_contents("debug.log", "❌ Error: Contraseña incorrecta.\n", FILE_APPEND);
        return false;
    }
    
    public function guardarSesion($Id_Cliente, $token) {
        $query = "INSERT INTO sesiones (Id_Cliente, token) VALUES (:Id_Cliente, :token)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Id_Cliente", $Id_Cliente);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

    public function cerrarSesion($token) {
        $query = "DELETE FROM sesiones WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }
}
?>
