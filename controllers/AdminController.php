<?php
/**
 * Controlador de Administrador
 * Maneja el dashboard y funciones administrativas
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/LugarTuristico.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Comentario.php';

class AdminController {
    
    private $usuarioModel;
    private $lugarModel;
    private $tourModel;
    private $reservaModel;
    private $comentarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->lugarModel = new LugarTuristico();
        $this->tourModel = new Tour();
        $this->reservaModel = new Reserva();
        $this->comentarioModel = new Comentario();
    }

    /**
     * Muestra el dashboard administrativo
     */
    public function dashboard() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para acceder', 'error');
            redirect('');
        }

        // Estadísticas generales
        $totalUsuarios = $this->usuarioModel->count();
        $totalTuristas = $this->usuarioModel->contarPorRol('turista');
        $totalLugares = $this->lugarModel->count();
        $totalTours = $this->tourModel->count();
        
        // Estadísticas de reservas
        $estadisticasReservas = $this->reservaModel->getEstadisticas();
        $ingresosTotales = $this->reservaModel->calcularIngresosTotales();
        
        // Estadísticas de comentarios
        $estadisticasComentarios = $this->comentarioModel->getEstadisticas();
        
        // Tours más reservados
        $toursMasReservados = $this->tourModel->getMasReservados(5);
        
        // Lugares más comentados
        $lugaresMasComentados = $this->lugarModel->getMasComentados(5);
        
        // Próximas reservas
        $proximasReservas = $this->reservaModel->getProximas(10);
        
        // Últimos comentarios
        $ultimosComentarios = $this->comentarioModel->getUltimos(5);
        
        // Estadísticas de ingresos por tour
        $estadisticasIngresos = $this->tourModel->getEstadisticasIngresos();
        
        // Lugares por categoría
        $lugaresPorCategoria = $this->lugarModel->contarPorCategoria();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
}