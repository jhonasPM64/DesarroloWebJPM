<?php
require_once "conexion.php";
$buscar = isset($_GET["buscar"]) ? trim($_GET["buscar"]) : "";
$categoria = isset($_GET["categoria"]) ? (int)$_GET["categoria"] : 0;
$sql = "SELECT p.id, p.nombre, p.precio, p.imagen, p.disponible, c.nombre AS categoria
FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id
WHERE p.nombre LIKE ? AND (? = 0 OR p.categoria_id = ?) ORDER BY p.id";
$consulta = $conexion->prepare($sql);
$termino = "%" . $buscar . "%";
$consulta->bind_param("sii", $termino, $categoria, $categoria);
$consulta->execute();
$resultado = $consulta->get_result();
$productos = [];
while ($fila = $resultado->fetch_assoc()) $productos[] = [
    "id" => (int)$fila["id"], "nombre" => $fila["nombre"], "precio" => (float)$fila["precio"],
    "imagen" => $fila["imagen"], "disponible" => (bool)(int)$fila["disponible"], "categoria" => $fila["categoria"]
];
responder(200, $productos);
