<?php
require_once "proteger.php";
require_once "conexion.php";

$buscar = trim($_GET['buscar'] ?? '');
$orden = $_GET['orden'] ?? 'nombres';

$lista_blanca_orden = ['nombres', 'apellidos', 'ci', 'fecha_nacimiento'];
if (!in_array($orden, $lista_blanca_orden)) {
    $orden = 'nombres';
}

$sql = "SELECT id, fotografia, nombres, apellidos, ci, telefono, fecha_nacimiento FROM pacientes";
$parametros_tipo = "";
$parametros_valores = [];

if ($buscar !== "") {
    $sql .= " WHERE nombres LIKE ? OR apellidos LIKE ? OR ci LIKE ?";
    $patron = "%" . $buscar . "%";
    $parametros_tipo = "sss";
    $parametros_valores = [$patron, $patron, $patron];
}

$sql .= " ORDER BY {$orden} ASC";

$stmt = $conn->prepare($sql);

if ($buscar !== "") {
    $stmt->bind_param($parametros_tipo, ...$parametros_valores);
}
$stmt->execute();
$resultado = $stmt->get_result();
$pacientes = [];
while ($fila = $resultado->fetch_assoc()) {
    $pacientes[] = $fila;
}
$stmt->close();

$total_encontrados = count($pacientes);
$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pacientes - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="contenedor">
        <?php if (isset($_SESSION['error_permiso'])): ?>
            <div class="aviso aviso-error">
                <?php 
                    echo htmlspecialchars($_SESSION['error_permiso']); 
                    unset($_SESSION['error_permiso']);
                ?>
            </div>
        <?php endif; ?>
        <h2>Pacientes (<?php echo $total_encontrados; ?> encontrados)</h2>
        <div class="panel">
            <form action="listar.php" method="GET" class="barra">
                <div style="flex: 1 1 300px;">
                    <label for="buscar">Buscar por nombre, apellido o CI</label>
                    <input type="text" id="buscar" name="buscar" value="<?php echo htmlspecialchars($buscar); ?>">
                </div>
                <div>
                    <button type="submit" class="boton">Buscar</button>
                    <a href="listar.php" class="boton boton-sec">Limpiar</a>
                </div>
            </form>
            <?php if ($es_admin): ?>
                <div style="margin-top: 15px; text-align: right;">
                    <a href="form_insertar.php" class="boton boton-ok">+ Nuevo paciente</a>
                </div>
            <?php endif; ?>
        </div>
        <p style="font-size: 13px; color: #5a6a7e; margin-bottom: 10px;">Haga clic en el título de una columna para ordenar</p>
        <?php if ($total_encontrados > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th><a href="listar.php?orden=nombres&buscar=<?php echo urlencode($buscar); ?>">Nombres</a></th>
                        <th><a href="listar.php?orden=apellidos&buscar=<?php echo urlencode($buscar); ?>">Apellidos</a></th>
                        <th><a href="listar.php?orden=ci&buscar=<?php echo urlencode($buscar); ?>">CI</a></th>
                        <th>Teléfono</th>
                        <th><a href="listar.php?orden=fecha_nacimiento&buscar=<?php echo urlencode($buscar); ?>">Nacimiento</a></th>
                        <?php if ($es_admin): ?>
                            <th style="text-align: center;">Operaciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $p): ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php if (!empty($p['fotografia']) && file_exists("images/" . $p['fotografia'])): ?>
                                    <img src="images/<?php echo htmlspecialchars($p['fotografia']); ?>" alt="Foto" class="foto">
                                <?php else: ?>
                                    <div class="sinfoto" style="margin: auto;">S/F</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($p['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($p['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($p['ci']); ?></td>
                            <td><?php echo htmlspecialchars($p['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($p['fecha_nacimiento']); ?></td>
                            <?php if ($es_admin): ?>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="form_editar.php?id=<?php echo $p['id']; ?>" class="boton mini">Editar</a>
                                    <a href="delete.php?id=<?php echo $p['id']; ?>" class="boton boton-no mini" onclick="return confirm('¿Está seguro de eliminar este paciente?');">Eliminar</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="aviso" style="border-left-color: var(--dorado); background: #fef9e7;">
                No se encontraron pacientes que coincidan con la búsqueda.
            </div>
        <?php endif; ?>
    </div>
    <footer class="pie">
        SIS-256 - Laboratorio de Desarrollo Web
    </footer>
</body>
</html>