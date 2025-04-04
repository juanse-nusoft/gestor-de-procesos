<?php

// Directorio donde se guardarán las imágenes
$upload_dir = 'uploads/';

// Verificar si el directorio existe, si no, crearlo
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Procesar la imagen subida
if ($_FILES['file']) {
    $file = $_FILES['file'];
    $file_name = uniqid() . '-' . basename($file['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // Devolver la URL de la imagen subida
        echo json_encode(['location' => '/' . $file_path]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al subir la imagen.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se recibió ninguna imagen.']);
}