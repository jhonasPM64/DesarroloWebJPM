<?php
session_start();

if (!isset($_SESSION["correo"])) {
    header("Location: form_login.php?error=2");
    exit();
}
?>