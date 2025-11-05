<?php
/**
 * Clase Utils
 * Funciones auxiliares y utilidades generales
 */

class Utils {
    
    /**
     * Sanitiza una cadena de texto
     * @param string $data
     * @return string
     */
     public static function sanitize($data) {
          if (is_array($data)) {
               return array_map([self::class, 'sanitize'], $data);
          }
          
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
          return $data;
     }
    
    /**
     * Limpia texto para usar en URLs
     * @param string $text
     * @return string
     */
     public static function slugify($text) {
          // Reemplazar caracteres especiales
          $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
          $text = preg_replace('/[^a-zA-Z0-9\-]/', '-', $text);
          $text = preg_replace('/-+/', '-', $text);
          $text = trim($text, '-');
          return strtolower($text);
     }
    
    /**
     * Trunca un texto a una longitud específica
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
     public static function truncate($text, $length = 100, $suffix = '...') {
          if (strlen($text) <= $length) {
               return $text;
          }
          
          $truncated = substr($text, 0, $length);
          $lastSpace = strrpos($truncated, ' ');
          
          if ($lastSpace !== false) {
               $truncated = substr($truncated, 0, $lastSpace);
          }
          
          return $truncated . $suffix;
     }
    
    /**
     * Formatea un número como precio en bolivianos
     * @param float $precio
     * @param bool $incluirSimbolo
     * @return string
     */
     public static function formatPrecio($precio, $incluirSimbolo = true) {
          $formatted = number_format($precio, 2, ',', '.');
          return $incluirSimbolo ? 'Bs. ' . $formatted : $formatted;
     }
    
    /**
     * Formatea una fecha en español
     * @param string $fecha Fecha en formato Y-m-d
     * @param bool $incluirHora
     * @return string
     */
     public static function formatFecha($fecha, $incluirHora = false) {
          $meses = [
               1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
               5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
               9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
          ];
          
          $timestamp = strtotime($fecha);
          $dia = date('d', $timestamp);
          $mes = $meses[(int)date('m', $timestamp)];
          $anio = date('Y', $timestamp);
          
          $resultado = "$dia de $mes de $anio";
          
          if ($incluirHora) {
               $hora = date('H:i', $timestamp);
               $resultado .= " a las $hora";
          }
          
          return $resultado;
     }
    
    /**
     * Formatea una fecha de forma relativa (hace X tiempo)
     * @param string $fecha
     * @return string
     */
    public static function formatFechaRelativa($fecha) {
        $timestamp = strtotime($fecha);
        $diferencia = time() - $timestamp;
        
        if ($diferencia < 60) {
            return 'Hace menos de un minuto';
        } elseif ($diferencia < 3600) {
            $minutos = floor($diferencia / 60);
            return "Hace $minutos " . ($minutos == 1 ? 'minuto' : 'minutos');
        } elseif ($diferencia < 86400) {
            $horas = floor($diferencia / 3600);
            return "Hace $horas " . ($horas == 1 ? 'hora' : 'horas');
        } elseif ($diferencia < 604800) {
            $dias = floor($diferencia / 86400);
            return "Hace $dias " . ($dias == 1 ? 'día' : 'días');
        } else {
            return self::formatFecha($fecha);
        }
    }
    
    /**
     * Genera estrellas de calificación en HTML
     * @param float $calificacion
     * @param int $max
     * @return string
     */
    public static function generarEstrellas($calificacion, $max = 5) {
        $estrellas = '';
        $calificacionRedondeada = round($calificacion * 2) / 2; // Redondear a 0.5
        
        for ($i = 1; $i <= $max; $i++) {
            if ($i <= $calificacionRedondeada) {
                $estrellas .= '★'; // Estrella llena
            } elseif ($i - 0.5 == $calificacionRedondeada) {
                $estrellas .= '⯨'; // Media estrella
            } else {
                $estrellas .= '☆'; // Estrella vacía
            }
        }
        
        return $estrellas;
    }
    
    /**
     * Convierte un array a opciones de select HTML
     * @param array $array
     * @param string $selected
     * @return string
     */
    public static function arrayToOptions($array, $selected = '') {
        $options = '';
        foreach ($array as $key => $value) {
            $isSelected = ($key == $selected) ? 'selected' : '';
            $options .= "<option value=\"$key\" $isSelected>$value</option>";
        }
        return $options;
    }
    
    /**
     * Genera un token aleatorio
     * @param int $length
     * @return string
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Valida y limpia un número de teléfono
     * @param string $telefono
     * @return string|null
     */
    public static function limpiarTelefono($telefono) {
        // Remover todo excepto números y el símbolo +
        $telefono = preg_replace('/[^0-9+]/', '', $telefono);
        
        if (empty($telefono)) {
            return null;
        }
        
        return $telefono;
    }
    
    /**
     * Calcula el tiempo de lectura estimado
     * @param string $texto
     * @return int Minutos de lectura
     */
    public static function tiempoLectura($texto) {
        $palabras = str_word_count(strip_tags($texto));
        $minutos = ceil($palabras / 200); // Promedio 200 palabras por minuto
        return max(1, $minutos);
    }
    
    /**
     * Obtiene las iniciales de un nombre
     * @param string $nombre
     * @return string
     */
    public static function obtenerIniciales($nombre) {
        $palabras = explode(' ', $nombre);
        $iniciales = '';
        
        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $iniciales .= strtoupper(substr($palabra, 0, 1));
            }
            
            if (strlen($iniciales) >= 2) {
                break;
            }
        }
        
        return $iniciales;
    }
    
    /**
     * Genera un color hexadecimal aleatorio
     * @return string
     */
    public static function colorAleatorio() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Verifica si una cadena contiene otra (insensible a mayúsculas)
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function contiene($haystack, $needle) {
        return stripos($haystack, $needle) !== false;
    }
    
    /**
     * Redirecciona a una URL
     * @param string $url
     * @param int $statusCode
     */
    public static function redirect($url, $statusCode = 302) {
        header('Location: ' . $url, true, $statusCode);
        exit();
    }
    
    /**
     * Obtiene la URL actual
     * @return string
     */
    public static function getCurrentUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Obtiene el método HTTP de la petición
     * @return string
     */
    public static function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Verifica si la petición es POST
     * @return bool
     */
    public static function isPost() {
        return self::getRequestMethod() === 'POST';
    }
    
    /**
     * Verifica si la petición es GET
     * @return bool
     */
    public static function isGet() {
        return self::getRequestMethod() === 'GET';
    }
    
    /**
     * Obtiene el IP del cliente
     * @return string
     */
    public static function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        }
    }
    
    /**
     * Debug: imprime variable con formato
     * @param mixed $var
     * @param bool $die
     */
    public static function dd($var, $die = true) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        
        if ($die) {
            die();
        }
    }
    
    /**
     * Genera un color basado en una cadena (para avatares)
     * @param string $string
     * @return string
     */
    public static function stringToColor($string) {
        $hash = md5($string);
        $color = '#';
        
        for ($i = 0; $i < 3; $i++) {
            $value = hexdec(substr($hash, $i * 2, 2));
            $color .= str_pad(dechex($value), 2, '0', STR_PAD_LEFT);
        }
        
        return $color;
    }
    
    /**
     * Convierte bytes a formato legible
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}