<?php
// Incluir archivo de configuración de la base de datos
require_once '../../../config.php';

// Función para limpiar la entrada de datos
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Obtener los datos del formulario
$first_name = clean_input($_POST["first_name"]);
$last_name = clean_input($_POST["last_name"]);
$email = clean_input($_POST["email"]);
$password = clean_input($_POST["password"]);
$confirm_password = clean_input($_POST["confirm_password"]);

// Verificar si las contraseñas coinciden
if ($password !== $confirm_password) {
    die("Las contraseñas no coinciden.");
}

// Verificar si el correo electrónico ya está registrado
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("El correo electrónico ya está registrado.");
}

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar los datos del nuevo usuario en la base de datos
$sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

if ($stmt->execute()) {
    // Enviar correo electrónico de bienvenida
    $postData = [
        'email' => $email,
        'firstName' => $first_name,
        'lastName' => $last_name
    ];

    $ch = curl_init('http://localhost:3000/send-email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === FALSE) {
        echo "Error al enviar el correo electrónico.";
    } else {
        // Redirigir al usuario al dashboard después de registrarse
        header("Location: ../../dashboard/dashboard.html");
        exit();
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
