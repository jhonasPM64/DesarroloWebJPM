<?php
require_once "proteger.php";
require_once "conexion.php";
$estado_filtro = $_GET['estado'] ?? '';
$sql = "SELECT 
            citas.id, 
            citas.fecha, 
            citas.hora, 
            citas.motivo, 
            citas.estado, 
            CONCAT(pacientes.nombres, ' ', pacientes.apellidos) AS paciente, 
            CONCAT(medicos.nombres, ' ', medicos.apellidos) AS medico,
            especialidades.nombre AS especialidad
        FROM citas
        INNER JOIN pacientes ON citas.paciente_id = pacientes.id
        INNER JOIN medicos ON citas.medico_id = medicos.id
        INNER JOIN especialidades ON medicos.especialidad_id = especialidades.id";
if ($estado_filtro !== '') {
    $sql .= " WHERE citas.estado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $estado_filtro);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
} else {
    $resultado = $conn->query($sql);
}
$citas = [];
while ($fila = $resultado->fetch_assoc()) {
    $citas[] = $fila;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Citas - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="contenedor">
        <h2>Agenda Médica (Citas Registradas)</h2>
        <div class="panel">
            <form action="agenda.php" method="GET" class="barra">
                <div style="flex: 1 1 250px;">
                    <label for="estado">Filtrar por estado</label>
                    <select id="estado" name="estado">
                        <option value="">-- Todos los estados --</option>
                        <option value="pendiente" <?php if($estado_filtro === 'pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="atendida" <?php if($estado_filtro === 'atendida') echo 'selected'; ?>>Atendida</option>
                        <option value="cancelada" <?php if($estado_filtro === 'cancelada') echo 'selected'; ?>>Cancelada</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="boton">Filtrar</button>
                    <a href="agenda.php" class="boton boton-sec">Ver Todos</a>
                </div>
            </form>
        </div>
        <?php if (count($citas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Paciente</th>
                        <th>Médico (Especialidad)</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['fecha'] . ' ' . $cita['hora']); ?></td>
                            <td><?php echo htmlspecialchars($cita['paciente']); ?></td>
                            <td><?php echo htmlspecialchars($cita['medico'] . ' (' . $cita['especialidad'] . ')'); ?></td>
                            <td><?php echo htmlspecialchars($cita['motivo']); ?></td>
                            <td style="text-align: center;">
                                <?php 
                                    $clase_etiqueta = 'et-pendiente';
                                    if ($cita['estado'] === 'atendida') $clase_etiqueta = 'et-atendida';
                                    if ($cita['estado'] === 'cancelada') $clase_etiqueta = 'et-cancelada';
                                ?>
                                <span class="etiqueta <?php echo $clase_etiqueta; ?>">
                                    <?php echo htmlspecialchars($cita['estado']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="aviso" style="border-left-color: var(--dorado); background: #fef9e7;">
                No se encontraron citas registradas con el filtro seleccionado.
            </div>
        <?php endif; ?>
    </div>
    <footer class="pie">
        SIS-256 - Laboratorio de Desarrollo Web
    </footer>
</body>
</html>