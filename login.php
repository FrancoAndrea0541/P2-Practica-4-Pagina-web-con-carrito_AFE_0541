<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    // Buscar si existe un usuario con ese correo Y esa contraseña
    $sql = "SELECT id, nombre FROM usuarios WHERE correo = ? AND password = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $row = $resultado->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['usuario_nombre'] = $row['nombre'];
        
        echo "Bienvenido/a, " . htmlspecialchars($row['nombre']) . ". <a href='index.html'>Ir a Inicio</a>";
    } else {
        echo "Correo o contraseña incorrectos. <a href='iniciosesion.html'>Intentar de nuevo</a>";
    }

    $stmt->close();
    $conexion->close();
}
?>