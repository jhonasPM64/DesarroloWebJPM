<?php
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $ci = trim($_POST['ci'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    if ($nombres === '' || $apellidos === '' || $ci === '' || $fecha_nacimiento === '') {
        die("Error: Faltan campos obligatorios por completar.");
    }
    $fotografia = null;
    if (isset($_FILES['fotografia'])) {
        $fotografia = subirFotografia($_FILES['fotografia']); // Devuelve el nombre del archivo guardado o null
    }
    $sql = "INSERT INTO pacientes (nombres, apellidos, ci, telefono, fecha_nacimiento, fotografia) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssss", $nombres, $apellidos, $ci, $telefono, $fecha_nacimiento, $fotografia);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: listar.php?exito=1");
            exit();
        } else {
            $error_db = $stmt->error;
            $stmt->close();
            $conn->close();
            die("Error al registrar el paciente en la base de datos: " . htmlspecialchars($error_db));
        }
    } else {
        die("Error en la preparación de la consulta.");
    }
} else {
    header("Location: listar.php");
    exit();
}
?>