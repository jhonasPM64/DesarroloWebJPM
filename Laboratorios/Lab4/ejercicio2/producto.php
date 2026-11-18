<?php
require_once "conexion.php";
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$sql = "SELECT p.id, p.nombre, p.precio, p.imagen, p.disponible, p.descripcion, c.nombre AS categoria
FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id WHERE p.id = ?";
$consulta = $conexion->prepare($sql);
$consulta->bind_param("i", $id);
$consulta->execute();
$producto = $consulta->get_result()->fetch_assoc();
if (!$producto) responder(404, ["exito" => false, "mensaje" => "Producto no encontrado"]);
responder(200, [
    "id" => (int)$producto["id"], "nombre" => $producto["nombre"], "precio" => (float)$producto["precio"],
    "imagen" => $producto["imagen"], "disponible" => (bool)(int)$producto["disponible"],
    "descripcion" => $producto["descripcion"], "categoria" => $producto["categoria"]
]);
