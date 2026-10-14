<?php
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: listar.php");
    exit();
}
$stmt = $conn->prepare("SELECT id, nombres, apellidos, ci, telefono, fecha_nacimiento, fotografia FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows !== 1) {
    $stmt->close();
    header("Location: listar.php");
    exit();
}
$paciente = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="contenedor">
        <h2>Editar Paciente</h2>
        <div class="panel">
            <form action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $paciente['id']; ?>">
                <input type="hidden" name="foto_actual" value="<?php echo htmlspecialchars($paciente['fotografia'] ?? ''); ?>">
                <div class="fila">
                    <div class="campo">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($paciente['nombres']); ?>" required>
                    </div>
                    <div class="campo">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($paciente['apellidos']); ?>" required>
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="ci">CI *</label>
                        <input type="text" id="ci" name="ci" value="<?php echo htmlspecialchars($paciente['ci']); ?>" required>
                    </div>
                    <div class="campo">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paciente['telefono'] ?? ''); ?>">
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?>" required>
                    </div>
                    <div class="campo">
                        <label for="fotografia">Nueva Fotografía (opcional)</label>
                        <input type="file" id="fotografia" name="fotografia" accept="image/*">
                        <?php if (!empty($paciente['fotografia'])): ?>
                            <small style="display: block; margin-top: 5px;">Fotografía actual: <?php echo htmlspecialchars($paciente['fotografia']); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="boton boton-ok">Actualizar Paciente</button>
                    <a href="listar.php" class="boton boton-sec">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="pie">
        SIS-256 - Laboratorio de Desarrollo Web
    </footer>

</body>
</html>