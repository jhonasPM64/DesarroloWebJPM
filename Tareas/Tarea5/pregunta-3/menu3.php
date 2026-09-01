<?php
include("pizarra.php");
session_start();

if (isset($_POST['palabra'])) {
    $palabra = $_POST['palabra'];
    $color = $_POST['color'];
    $color_fondo = $_POST['color_fondo'];

    $_SESSION['pizarra'] = new Pizarra($palabra, $color, $color_fondo);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú 3</title>
</head>
<body>
    <h2>Menú de Opciones</h2>
    
    <ul>
        <li><a href="menu3.php?opcion=triangulo">Triángulo</a></li>
    </ul>
    <hr>
    <?php
    if (isset($_GET['opcion']) && $_GET['opcion'] == 'triangulo') {
        $pizarra = $_SESSION['pizarra'];
        $pizarra->triangulo();
    }
    ?>
    <br>
    <a href="formulario.html">Volver al formulario</a>
</body>
</html>