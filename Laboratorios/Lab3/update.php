<?php
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $ci = trim($_POST['ci'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $foto_actual = $_POST['foto_actual'] ?? '';

    if ($id <= 0 || $nombres === '' || $apellidos === '' || $ci === '' || $fecha_nacimiento === '') {
        die("Error: Datos incompletos para la actualización.");
    }
    $fotografia = $foto_actual;
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $nueva_foto = subirFotografia($_FILES['fotografia']);
        if ($nueva_foto) {
            $fotografia = $nueva_foto;
            if ($foto_actual !== '' && file_exists("images/" . $foto_actual)) {
                @unlink("images/" . $foto_actual);
            }
        }
    }
    $sql = "UPDATE pacientes SET nombres = ?, apellidos = ?, ci = ?, telefono = ?, fecha_nacimiento = ?, fotografia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssi", $nombres, $apellidos, $ci, $telefono, $fecha_nacimiento, $fotografia, $id);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: listar.php");
            exit();
        } else {
            $err = $stmt->error;
            $stmt->close();
            $conn->close();
            die("Error al actualizar: " . htmlspecialchars($err));
        }
    } else {
        die("Error en la preparación de la consulta.");
    }
} else {
    header("Location: listar.php");
    exit();
}
?>