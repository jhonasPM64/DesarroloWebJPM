<?php
session_start();
require_once 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $clave_sha1 = sha1($clave);
    $sql = "SELECT id, correo, nombre, rol FROM usuarios WHERE correo = ? AND clave = ? LIMIT 1";
    
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $correo, $clave_sha1);        
        $stmt->execute();        
        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            $stmt->close();
            $conn->close();
            header("Location: panel.php");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: form_login.php?error=1");
            exit();
        }
    } else {
        die("Error en el servidor al procesar la consulta.");
    }
} else {
    header("Location: form_login.php");
    exit();
}
?>