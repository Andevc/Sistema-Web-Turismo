<?php
/**
 * Controlador de Tours
 * Maneja CRUD y búsquedas de tours
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../helpers/Upload.php';

class TourController {
    
    private $tourModel;
    private $reservaModel;

    public function __construct() {
        $this->tourModel = new Tour();
        $this->reservaModel = new Reserva();
    }

    /**
     * Lista todos los tours con filtros
     */
    public function index() {
        // Obtener filtros de búsqueda
        $filtros = [
            'precio_min' => $_GET['precio_min'] ?? '',
            'precio_max' => $_GET['precio_max'] ?? '',
            'busqueda' => $_GET['busqueda'] ?? '',
            'orden' => $_GET['orden'] ?? 'nombre_asc'
        ];

        // Buscar tours con filtros
        if (!empty($filtros['busqueda']) || $filtros['precio_min'] !== '' || $filtros['precio_max'] !== '') {
            $tours = $this->tourModel->buscar($filtros);
        } else {
            $tours = $this->tourModel->getAll();
        }

        require_once __DIR__ . '/../views/tours/index.php';
    }

    /**
     * Muestra el detalle de un tour
     */
    public function detalle() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        $tour = $this->tourModel->getDetalleCompleto($id);

        if (!$tour) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        // Verificar disponibilidad para hoy (ejemplo)
        $fecha_ejemplo = date('Y-m-d', strtotime('+7 days'));
        $disponibilidad = $this->tourModel->verificarDisponibilidad($id, $fecha_ejemplo);

        require_once __DIR__ . '/../views/tours/detalle.php';
    }

    /**
     * Muestra el formulario para crear un tour (solo admin)
     */
    public function crear() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para acceder', 'error');
            redirect('');
        }

        require_once __DIR__ . '/../views/tours/crear.php';
    }

    /**
     * Guarda un nuevo tour
     */
    public function guardar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('tours/crear');
        }

        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'precio' => sanitize($_POST['precio'] ?? ''),
            'cupo_maximo' => sanitize($_POST['cupo_maximo'] ?? '')
        ];

        $resultado = $this->tourModel->crear($data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('tours');
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('tours/crear');
        }
    }

    /**
     * Muestra el formulario para editar un tour (solo admin)
     */
    public function editar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para acceder', 'error');
            redirect('');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        $tour = $this->tourModel->getById($id);

        if (!$tour) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        require_once __DIR__ . '/../views/tours/editar.php';
    }

    /**
     * Actualiza un tour
     */
    public function actualizar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('tours');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'precio' => sanitize($_POST['precio'] ?? ''),
            'cupo_maximo' => sanitize($_POST['cupo_maximo'] ?? '')
        ];

        $resultado = $this->tourModel->actualizar($id, $data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('tours/detalle?id=' . $id);
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('tours/editar?id=' . $id);
        }
    }

    /**
     * Elimina un tour (solo admin)
     */
    public function eliminar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        $resultado = $this->tourModel->eliminar($id);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('tours');
    }
}