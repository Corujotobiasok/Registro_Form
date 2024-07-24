<?php
$servername = "localhost";
$username = "root";
$password = ""; // Deja la contraseña vacía si no has establecido una
$dbname = "moovika";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
