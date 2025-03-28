<?php
session_start();
require_once "Rutas.php";
$rutas = new Rutas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h1>BIENVENIDO A HANDYMANTOTAL</h1>
    <?php if (isset($_SESSION['cliente'])) : ?>
        <p>Hola, <?= htmlspecialchars($_SESSION['cliente']) ?> | 
            <a href="logout.php">Cerrar sesión</a>
        </p>
    <?php else : ?>
        <p><a href="login.php">Iniciar sesión</a> | <a href="register.php">Registrarse</a></p>
    <?php endif; ?>
</body>
</html>
