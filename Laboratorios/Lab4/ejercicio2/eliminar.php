<?php
require_once "conexion.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") responder(405, ["exito" => false, "mensaje" => "Método no permitido"]);
$id = (int)($_POST["id"] ?? 0);
$consulta = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
if ($consulta->affected_rows === 0) responder(404, ["exito" => false, "mensaje" => "Producto no encontrado"]);
responder(200, ["exito" => true, "mensaje" => "Producto eliminado correctamente"]);
