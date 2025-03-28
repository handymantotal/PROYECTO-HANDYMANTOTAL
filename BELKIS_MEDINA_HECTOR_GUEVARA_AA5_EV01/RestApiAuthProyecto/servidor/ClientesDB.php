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
            return true;
    
        } catch (Exception $e) {
            return false;
        }
    }
    public function iniciarSesion($correo_electronico, $contrasena) {
        $query = "SELECT Id_Cliente, Nombre, Contrasena FROM cliente WHERE Correo_electronico = :Correo_electronico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Correo_electronico", $correo_electronico);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$cliente) {
            return false;
        }

        if (password_verify($contrasena, $cliente["Contrasena"])) {
            return $cliente["Id_Cliente"];
        }
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
