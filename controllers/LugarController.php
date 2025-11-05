<?php
/**
 * Controlador de Lugares Turísticos
 * Maneja CRUD y búsquedas de lugares turísticos
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/LugarTuristico.php';
require_once __DIR__ . '/../models/Comentario.php';

class LugarController {
    
    private $lugarModel;
    private $comentarioModel;

    public function __construct() {
        $this->lugarModel = new LugarTuristico();
        $this->comentarioModel = new Comentario();
    }

    /**
     * Lista todos los lugares turísticos con filtros
     */
    public function index() {
        // Obtener filtros de búsqueda
        $filtros = [
            'categoria' => $_GET['categoria'] ?? '',
            'precio_min' => $_GET['precio_min'] ?? '',
            'precio_max' => $_GET['precio_max'] ?? '',
            'busqueda' => $_GET['busqueda'] ?? ''
        ];

        // Buscar lugares con filtros
        if (!empty($filtros['categoria']) || !empty($filtros['busqueda']) || 
            $filtros['precio_min'] !== '' || $filtros['precio_max'] !== '') {
            $lugares = $this->lugarModel->buscar($filtros);
        } else {
            $lugares = $this->lugarModel->getAll();
        }

        $categorias = CATEGORIAS_LUGARES;

        require_once __DIR__ . '/../views/lugares/index.php';
    }

    /**
     * Muestra el detalle de un lugar turístico
     */
    public function detalle() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        $lugar = $this->lugarModel->getDetalleCompleto($id);

        if (!$lugar) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        // Obtener comentarios del lugar
        $comentarios = $this->comentarioModel->getPorLugar($id);

        // Obtener promedio de calificación
        $estadisticas = $this->comentarioModel->getPromedioCalificacion($id);

        require_once __DIR__ . '/../views/lugares/detalle.php';
    }

    /**
     * Muestra el formulario para crear un lugar (solo admin)
     */
    public function crear() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para acceder', 'error');
            redirect('');
        }

        $categorias = CATEGORIAS_LUGARES;

        require_once __DIR__ . '/../views/lugares/crear.php';
    }

    /**
     * Guarda un nuevo lugar turístico
     */
    public function guardar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('lugares/crear');
        }

        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'categoria' => sanitize($_POST['categoria'] ?? ''),
            'direccion' => sanitize($_POST['direccion'] ?? ''),
            'precio_entrada' => sanitize($_POST['precio_entrada'] ?? ''),
            'horario_apertura' => sanitize($_POST['horario_apertura'] ?? ''),
            'horario_cierre' => sanitize($_POST['horario_cierre'] ?? ''),
            'imagen_lugar' => 'default.jpg'
        ];

        $resultado = $this->lugarModel->crear($data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('lugares');
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('lugares/crear');
        }
    }

    /**
     * Muestra el formulario para editar un lugar (solo admin)
     */
    public function editar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para acceder', 'error');
            redirect('');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        $lugar = $this->lugarModel->getById($id);

        if (!$lugar) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        $categorias = CATEGORIAS_LUGARES;

        require_once __DIR__ . '/../views/lugares/editar.php';
    }

    /**
     * Actualiza un lugar turístico
     */
    public function actualizar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('lugares');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'categoria' => sanitize($_POST['categoria'] ?? ''),
            'direccion' => sanitize($_POST['direccion'] ?? ''),
            'precio_entrada' => sanitize($_POST['precio_entrada'] ?? ''),
            'horario_apertura' => sanitize($_POST['horario_apertura'] ?? ''),
            'horario_cierre' => sanitize($_POST['horario_cierre'] ?? ''),
            'imagen_lugar' => 'default.jpg'
        ];

        $resultado = $this->lugarModel->actualizar($id, $data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('lugares/detalle?id=' . $id);
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('lugares/editar?id=' . $id);
        }
    }

    /**
     * Elimina un lugar turístico (solo admin)
     */
    public function eliminar() {
        if (!isAdmin()) {
            setFlashMessage('No tienes permiso para realizar esta acción', 'error');
            redirect('');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Lugar no encontrado', 'error');
            redirect('lugares');
        }

        $resultado = $this->lugarModel->eliminar($id);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('lugares');
    }
}