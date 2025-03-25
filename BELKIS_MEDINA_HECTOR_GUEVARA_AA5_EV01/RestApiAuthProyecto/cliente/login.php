<?php
session_start();
require_once('Rutas.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rutas = new Rutas();

    $correo_electronico = trim(filter_var($_POST["Correo_electronico"], FILTER_SANITIZE_EMAIL));
    $contrasena = trim($_POST["Contrasena"]);
    $data = json_encode(["Correo_electronico" => $correo_electronico, "Contrasena" => $contrasena]);

    $ch = curl_init($rutas->getloginApiUrl());
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $res = curl_exec($ch);
    file_put_contents("debug.log", "🔹 Respuesta JSON cruda de la API: " . $res . "\n", FILE_APPEND);
    
    curl_close($ch);

    // Decodificar la respuesta JSON
    $response = json_decode($res, true);
    file_put_contents("debug.log", "🔹 Respuesta decodificada en PHP: " . print_r($response, true) . "\n", FILE_APPEND);

    // Verificar la estructura de la respuesta
    if ($response && isset($response["status"]) && $response["status"] == "success") {
        if (isset($response["message"]["token"])) {
            file_put_contents("debug.log", "✅ Sesión iniciada correctamente en login.php\n", FILE_APPEND);
            $_SESSION["cliente"] = $correo_electronico;
            $_SESSION["token"] = $response["message"]["token"];
            header("Location: index.php");
            exit;
        } else {
            file_put_contents("debug.log", "⚠️ La respuesta no contiene un token\n", FILE_APPEND);
            $error = "Error en la autenticación";
        }
    } else {
        file_put_contents("debug.log", "❌ Error en login.php: " . print_r($response, true) . "\n", FILE_APPEND);
        $error = "Credenciales incorrectas";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST">
        <label>Correo_electronico:</label>
        <input type="Correo_electronico" name="Correo_electronico" required><br>
        <label>Contraseña:</label>
        <input type="password" name="Contrasena" required><br>
        <button type="submit">Ingresar</button>
    </form>
    <?= isset($error) ? "<p>$error</p>" : "" ?>
</body>
</html>
