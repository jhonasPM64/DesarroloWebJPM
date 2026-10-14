<?php
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM pacientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    try {
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: listar.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        $stmt->close();
        $conn->close();        
        session_start();
        $_SESSION['error_permiso'] = "No se puede eliminar el paciente porque tiene citas registradas asociadas (protegido por integridad referencial).";
        header("Location: listar.php");
        exit();
    }
}
header("Location: listar.php");
exit();
?>