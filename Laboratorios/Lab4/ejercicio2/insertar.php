<?php
require_once "conexion.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") responder(405, ["exito" => false, "mensaje" => "Método no permitido"]);

$nombre = trim($_POST["nombre"] ?? "");
$categoria_id = (int)($_POST["categoria_id"] ?? 0);
$precio = (float)($_POST["precio"] ?? 0);
$descripcion = trim($_POST["descripcion"] ?? "");
$disponible = (int)($_POST["disponible"] ?? 1);
$errores = [];
if (mb_strlen($nombre) < 3) $errores[] = "El nombre debe tener al menos 3 caracteres.";
if ($precio <= 0) $errores[] = "El precio debe ser mayor que cero.";
$consultaCategoria = $conexion->prepare("SELECT id FROM categorias WHERE id = ?");
$consultaCategoria->bind_param("i", $categoria_id);
$consultaCategoria->execute();
if (!$consultaCategoria->get_result()->fetch_assoc()) $errores[] = "La categoría seleccionada no es válida.";
if ($errores) responder(422, ["exito" => false, "errores" => $errores]);

$imagenNombre = "";
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $archivo = $_FILES["imagen"];
    if ($archivo["error"] !== UPLOAD_ERR_OK) responder(422, ["exito" => false, "errores" => ["Error al cargar la imagen."]]);
    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    if (!in_array($extension, ["jpg", "jpeg", "png", "webp"], true)) responder(422, ["exito" => false, "errores" => ["Extensión de imagen no permitida."]]);
    if ($archivo["size"] > 2 * 1024 * 1024) responder(422, ["exito" => false, "errores" => ["La imagen supera los 2 MB."]]);
    $imagenNombre = uniqid("producto_", true) . "." . $extension;
    if (!move_uploaded_file($archivo["tmp_name"], __DIR__ . "/../images/" . $imagenNombre)) responder(422, ["exito" => false, "errores" => ["No se pudo guardar la imagen."]]);
}

$consulta = $conexion->prepare("INSERT INTO productos (nombre, categoria_id, precio, descripcion, imagen, disponible) VALUES (?, ?, ?, ?, ?, ?)");
$consulta->bind_param("sidssi", $nombre, $categoria_id, $precio, $descripcion, $imagenNombre, $disponible);
if (!$consulta->execute()) responder(500, ["exito" => false, "errores" => ["No se pudo insertar el producto."]]);
responder(201, ["exito" => true, "id" => (int)$conexion->insert_id, "mensaje" => "Producto registrado correctamente"]);
