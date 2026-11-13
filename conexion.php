<?php
$host = "localhost";
$usuario = "root";
$password = ""; 
$bd = "kuromishop";

$conexion = new mysqli($host, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}
?>