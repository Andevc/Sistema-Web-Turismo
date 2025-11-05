<?php
/**
 * Controlador de Inicio
 * Maneja la página principal del sistema
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/LugarTuristico.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Comentario.php';

class InicioController {
    
    private $lugarModel;
    private $tourModel;
    private $comentarioModel;

    public function __construct() {
        $this->lugarModel = new LugarTuristico();
        $this->tourModel = new Tour();
        $this->comentarioModel = new Comentario();
    }

    /**
     * Muestra la página de inicio
     */
    public function index() {
        // Obtener lugares destacados (mejor calificados)
        $lugaresDestacados = $this->lugarModel->getDestacados(3);
        
        // Obtener tours más reservados
        $toursMasReservados = $this->tourModel->getMasReservados(3);
        
        // Obtener últimos comentarios
        $ultimosComentarios = $this->comentarioModel->getUltimos(5);
        
        // Obtener lugares por categoría para mostrar variedad
        $categorias = CATEGORIAS_LUGARES;
        
        // Cargar la vista
        require_once __DIR__ . '/../views/home/index.php';
    }
}