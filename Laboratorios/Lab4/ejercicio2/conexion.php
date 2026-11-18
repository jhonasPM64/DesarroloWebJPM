<?php
header("Content-Type: application/json; charset=utf-8");

$conexion = new mysqli("localhost", "root", "", "bd_cafeteria26");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["exito" => false, "mensaje" => "Error de conexión"], JSON_UNESCAPED_UNICODE);
    exit();
}
$conexion->set_charset("utf8mb4");

function responder($codigo, $datos) {
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit();
}
