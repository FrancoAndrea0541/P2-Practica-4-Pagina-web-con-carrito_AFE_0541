<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        die("Las contraseñas no coinciden. <a href='registro.html'>Volver</a>");
    }

    // Insertar la contraseña en texto plano
    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $correo, $password);

    if ($stmt->execute()) {
        echo "¡Registro exitoso! <a href='iniciosesion.html'>Iniciar Sesión</a>";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>