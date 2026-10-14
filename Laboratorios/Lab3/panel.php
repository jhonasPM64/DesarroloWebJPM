<?php
require_once "proteger.php";
require_once "conexion.php";
$total_pacientes = 0;
$total_medicos = 0;
$total_citas = 0;
if ($res = $conn->query("SELECT COUNT(*) AS total FROM pacientes")) {
    $total_pacientes = $res->fetch_assoc()['total'];
    $res->free();
}
if ($res = $conn->query("SELECT COUNT(*) AS total FROM medicos")) {
    $total_medicos = $res->fetch_assoc()['total'];
    $res->free();
}
if ($res = $conn->query("SELECT COUNT(*) AS total FROM citas WHERE estado = 'pendiente'")) {
    $total_citas = $res->fetch_assoc()['total'];
    $res->free();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Consultorio</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="contenedor">
        <div class="panel">
            <h2>Panel de Administración</h2>
            <p><strong>Usuario conectado:</strong> <?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION['correo']); ?> | <strong>Rol:</strong> <span class="etiqueta et-admin"><?php echo htmlspecialchars($_SESSION['rol']); ?></span></p>
            <p style="font-size: 11px; color: #5a6a7e;">ID de Sesión: <?php echo session_id(); ?></p>
        </div>
        <h3>Indicadores Generales del Sistema</h3>
        <div class="tarjetas">
            <div class="tarjeta">
                <div class="numero"><?php echo $total_pacientes; ?></div>
                <div class="texto">Pacientes Registrados</div>
            </div>
            <div class="tarjeta">
                <div class="numero"><?php echo $total_medicos; ?></div>
                <div class="texto">Médicos Registrados</div>
            </div>
            <div class="tarjeta">
                <div class="numero"><?php echo $total_citas; ?></div>
                <div class="texto">Citas Pendientes</div>
            </div>
        </div>
    </div>
    <footer class="pie">
        SIS-256 - Laboratorio de Desarrollo Web
    </footer>
    <?php 
$rol = $_SESSION['rol'] ?? 'user';
$clase_etiqueta = ($rol === 'admin') ? 'et-admin' : 'et-user';
?>
</body>
</html>