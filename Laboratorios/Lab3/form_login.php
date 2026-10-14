<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="login">
        <h1>Consultorio USFX</h1>
        <p class="sub">Sistema de pacientes y citas - SIS 256</p>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == '1'): ?>
                <div class="aviso aviso-error">Correo o contraseña incorrectos.</div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="autenticar.php" method="POST">
            <div class="campo">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="usuario@sis256.edu" required>
            </div>
            <div class="campo">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            <button type="submit" class="boton">Ingresar</button>
        </form>
        <div class="ayuda">
            <strong>Usuarios de prueba:</strong><br>
            admin@sis256.edu / 123 (rol admin)<br>
            recepcion@sis256.edu / 123 (rol user)
        </div>
    </div>
</body>
</html>