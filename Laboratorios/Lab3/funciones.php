<?php
function subirFotografia(array $archivo): ?string {
    if (!isset($archivo['error']) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $nombre_temporal = $archivo['tmp_name'];
        $nombre_original = basename($archivo['name']);        
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($extension, $extensiones_permitidas)) {
            return null;
        }
        $nombre_unico = time() . "_" . mt_rand(1000, 9999) . "." . $extension;
        $ruta_destino = "images/" . $nombre_unico;
        if (!is_dir("images")) {
            mkdir("images", 0755, true);
        }
        if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
            return $nombre_unico;
        }
    }
    return null;
}
?>