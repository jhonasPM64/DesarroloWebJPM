<?php
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Paciente - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="contenedor">
        <h2>Nuevo Paciente</h2>
        <div class="panel">
            <form action="insert.php" method="POST" enctype="multipart/form-data">
                
                <div class="fila">
                    <div class="campo">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" required>
                    </div>
                    <div class="campo">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" required>
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="ci">CI (Cédula de Identidad) *</label>
                        <input type="text" id="ci" name="ci" required>
                    </div>
                    <div class="campo">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono">
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="campo">
                        <label for="fotografia">Fotografía</label>
                        <input type="file" id="fotografia" name="fotografia" accept="image/*">
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="boton boton-ok">Guardar Paciente</button>
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