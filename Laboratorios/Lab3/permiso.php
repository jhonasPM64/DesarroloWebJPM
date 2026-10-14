<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    $_SESSION["error_permiso"] = "Acceso denegado: se requieren privilegios de administrador.";
    header("Location: listar.php");
    exit();
}
?>