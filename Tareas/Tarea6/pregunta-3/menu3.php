<?php
require_once 'estante.php';
session_start();

$mensaje = '';
$opcion = $_GET['opcion'] ?? 'mostrar';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        $cap = (int)($_POST['capacidad'] ?? 0);
        if ($cap > 0) {
            $_SESSION['estante'] = new Estante($cap);
            $mensaje = "Estante creado con capacidad máxima de $cap por nivel.";
            $opcion = 'mostrar';
        }
    } elseif ($accion === 'insertar') {
        if (isset($_SESSION['estante'])) {
            $nivel = (int)$_POST['nivel'];
            $elemento = $_POST['elemento'] ?? '';
            $mensaje = $_SESSION['estante']->insertar($nivel, $elemento);
            $opcion = 'mostrar';
        } else {
            $mensaje = "Primero debe crear un estante.";
        }
    } elseif ($accion === 'quitar') {
        if (isset($_SESSION['estante'])) {
            $nivel = (int)$_POST['nivel'];
            $mensaje = $_SESSION['estante']->quitar($nivel);
            $opcion = 'mostrar';
        } else {
            $mensaje = "Primero debe crear un estante.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Estante</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        ul.menu { list-style: none; padding: 0; display: flex; gap: 10px; margin-bottom: 25px; }
        ul.menu li a {
            text-decoration: none;
            background: #2b5797;
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
            font-weight: bold;
        }
        ul.menu li a:hover { background: #1b3863; }
        .bloque-accion { border: 1px solid #aaa; padding: 20px; width: fit-content; border-radius: 5px; }
        .mensaje { font-weight: bold; color: #b71c1c; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h2>Menú de Operaciones</h2>

    <!-- Las 4 opciones del menú -->
    <ul class="menu">
        <li><a href="menu3.php?opcion=crear">Crear Estante</a></li>
        <li><a href="menu3.php?opcion=insertar">Insertar</a></li>
        <li><a href="menu3.php?opcion=quitar">Quitar</a></li>
        <li><a href="menu3.php?opcion=mostrar">Mostrar Estante</a></li>
    </ul>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <div class="bloque-accion">
        <?php if ($opcion === 'crear'): ?>
            <h3>Crear Estante</h3>
            <form action="menu3.php" method="post">
                <input type="hidden" name="accion" value="crear">
                <label>Capacidad máxima por nivel:</label>
                <input type="number" name="capacidad" min="1" max="10" value="4" required>
                <button type="submit">Crear</button>
            </form>

        <?php elseif ($opcion === 'insertar'): ?>
            <h3>Insertar en Estante</h3>
            <form action="menu3.php" method="post">
                <input type="hidden" name="accion" value="insertar">
                <label>Nivel:</label>
                <select name="nivel">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <label>Elemento:</label>
                <input type="text" name="elemento" required>
                <button type="submit">Insertar</button>
            </form>

        <?php elseif ($opcion === 'quitar'): ?>
            <h3>Quitar de Estante</h3>
            <form action="menu3.php" method="post">
                <input type="hidden" name="accion" value="quitar">
                <label>Nivel del cual quitar:</label>
                <select name="nivel">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <button type="submit">Quitar último</button>
            </form>

        <?php else: ?>
            <h3>Vista del Estante</h3>
            <?php
            if (isset($_SESSION['estante'])) {
                $_SESSION['estante']->mostrarEstante();
            } else {
                echo "<p>El estante no ha sido creado todavía. Ve a la opción <b>Crear Estante</b>.</p>";
            }
            ?>
        <?php endif; ?>
    </div>

</body>
</html>