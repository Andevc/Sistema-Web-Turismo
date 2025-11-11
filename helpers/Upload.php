<?php
/**
 * Helper para manejo de uploads de archivos
 */

class Upload {
    
    /**
     * Subir imagen
     * @param array $file Archivo de $_FILES
     * @param string $directory Directorio destino (lugares/tours)
     * @return array [success, message, filename]
     */
    public static function imagen($file, $directory) {
        // Verificar que se subió un archivo
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => false, 'message' => 'No se seleccionó ningún archivo', 'filename' => null];
        }
        
        // Verificar errores
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error al subir el archivo', 'filename' => null];
        }
        
        // Verificar tamaño (5MB)
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'El archivo es demasiado grande (máx: 5MB)', 'filename' => null];
        }
        
        // Verificar tipo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido. Solo JPG, PNG o GIF', 'filename' => null];
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Directorio de destino
        $uploadDir = UPLOAD_DIR . $directory . '/';
        
        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $destination = $uploadDir . $filename;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'message' => 'Imagen subida exitosamente',
                'filename' => $filename
            ];
        }
        
        return ['success' => false, 'message' => 'Error al guardar el archivo', 'filename' => null];
    }
    
    /**
     * Eliminar imagen
     * @param string $filename
     * @param string $directory
     * @return bool
     */
    public static function eliminar($filename, $directory) {
        if ($filename === 'default.jpg' || empty($filename)) {
            return true;
        }
        
        $filepath = UPLOAD_DIR . $directory . '/' . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return true;
    }
    
    /**
     * Validar imagen sin subirla
     * @param array $file
     * @return array [valid, message]
     */
    public static function validarImagen($file) {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['valid' => true, 'message' => 'Sin archivo'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'Error en el archivo'];
        }
        
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['valid' => false, 'message' => 'Archivo muy grande'];
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['valid' => false, 'message' => 'Tipo de archivo no permitido'];
        }
        
        return ['valid' => true, 'message' => 'Válido'];
    }
}