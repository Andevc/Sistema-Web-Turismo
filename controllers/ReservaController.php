<?php
/**
 * Controlador de Reservas
 * Maneja la creación, edición y cancelación de reservas
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Tour.php';

class ReservaController {
    
    private $reservaModel;
    private $tourModel;

    public function __construct() {
        $this->reservaModel = new Reserva();
        $this->tourModel = new Tour();
    }

    /**
     * Muestra el formulario para crear una reserva
     */
    public function crear() {
        if (!isLoggedIn()) {
            setFlashMessage('Debes iniciar sesión para hacer una reserva', 'error');
            redirect('usuario/login');
        }

        $tour_id = $_GET['tour'] ?? null;

        if (!$tour_id) {
            setFlashMessage('Tour no especificado', 'error');
            redirect('tours');
        }

        $tour = $this->tourModel->getById($tour_id);

        if (!$tour) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        require_once __DIR__ . '/../views/reservas/crear.php';
    }

    /**
     * Guarda una nueva reserva
     */
    public function guardar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('tours');
        }

        $tour_id = $_POST['id_tour'] ?? null;
        $fecha_tour = sanitize($_POST['fecha_tour'] ?? '');
        $cantidad_personas = (int)($_POST['cantidad_personas'] ?? 1);

        if (!$tour_id || !$fecha_tour || $cantidad_personas < 1) {
            setFlashMessage('Datos incompletos', 'error');
            redirect('tours');
        }

        // Obtener información del tour
        $tour = $this->tourModel->getById($tour_id);

        if (!$tour) {
            setFlashMessage('Tour no encontrado', 'error');
            redirect('tours');
        }

        // Verificar disponibilidad
        $disponibilidad = $this->tourModel->verificarDisponibilidad($tour_id, $fecha_tour);

        if (!$disponibilidad['disponible'] || $disponibilidad['cupos_disponibles'] < $cantidad_personas) {
            setFlashMessage('No hay suficientes cupos disponibles para esa fecha', 'error');
            redirect('tours/detalle?id=' . $tour_id);
        }

        // Calcular precio total
        $precio_total = $tour['precio'] * $cantidad_personas;

        $data = [
            'id_usuario' => $_SESSION['usuario_id'],
            'id_tour' => $tour_id,
            'fecha_tour' => $fecha_tour,
            'cantidad_personas' => $cantidad_personas,
            'precio_total' => $precio_total
        ];

        $resultado = $this->reservaModel->crear($data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('reservas/confirmar?id=' . $resultado['id']);
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('tours/detalle?id=' . $tour_id);
        }
    }

    /**
     * Muestra la confirmación de una reserva
     */
    public function confirmar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Reserva no encontrada', 'error');
            redirect('usuario/mis-reservas');
        }

        $reserva = $this->reservaModel->getDetalleCompleto($id);

        if (!$reserva || $reserva['id_usuario'] != $_SESSION['usuario_id']) {
            setFlashMessage('Reserva no encontrada', 'error');
            redirect('usuario/mis-reservas');
        }

        require_once __DIR__ . '/../views/reservas/confirmar.php';
    }

    /**
     * Muestra el detalle de una reserva
     */
    public function detalle() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Reserva no encontrada', 'error');
            redirect('usuario/mis-reservas');
        }

        $reserva = $this->reservaModel->getDetalleCompleto($id);

        // Verificar permisos (usuario dueño o admin)
        if (!$reserva || (!isAdmin() && $reserva['id_usuario'] != $_SESSION['usuario_id'])) {
            setFlashMessage('No tienes permiso para ver esta reserva', 'error');
            redirect('usuario/mis-reservas');
        }

        require_once __DIR__ . '/../views/reservas/detalle.php';
    }

    /**
     * Muestra el formulario para editar una reserva
     */
    public function editar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Reserva no encontrada', 'error');
            redirect('usuario/mis-reservas');
        }

        $reserva = $this->reservaModel->getDetalleCompleto($id);

        // Verificar permisos
        if (!$reserva || (!isAdmin() && $reserva['id_usuario'] != $_SESSION['usuario_id'])) {
            setFlashMessage('No tienes permiso para editar esta reserva', 'error');
            redirect('usuario/mis-reservas');
        }

        require_once __DIR__ . '/../views/reservas/editar.php';
    }

    /**
     * Actualiza una reserva
     */
    public function actualizar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuario/mis-reservas');
        }

        $id = $_POST['id'] ?? null;
        $fecha_tour = sanitize($_POST['fecha_tour'] ?? '');
        $cantidad_personas = (int)($_POST['cantidad_personas'] ?? 1);

        if (!$id || !$fecha_tour || $cantidad_personas < 1) {
            setFlashMessage('Datos incompletos', 'error');
            redirect('usuario/mis-reservas');
        }

        // Verificar que la reserva existe y pertenece al usuario
        $reserva = $this->reservaModel->getById($id);

        if (!$reserva || (!isAdmin() && $reserva['id_usuario'] != $_SESSION['usuario_id'])) {
            setFlashMessage('No tienes permiso para editar esta reserva', 'error');
            redirect('usuario/mis-reservas');
        }

        // Obtener precio del tour
        $tour = $this->tourModel->getById($reserva['id_tour']);
        $precio_total = $tour['precio'] * $cantidad_personas;

        $data = [
            'fecha_tour' => $fecha_tour,
            'cantidad_personas' => $cantidad_personas,
            'precio_total' => $precio_total
        ];

        $resultado = $this->reservaModel->actualizar($id, $data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
            redirect('reservas/detalle?id=' . $id);
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('reservas/editar?id=' . $id);
        }
    }

    /**
     * Cancela una reserva
     */
    public function cancelar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Reserva no encontrada', 'error');
            redirect('usuario/mis-reservas');
        }

        // Admin puede cancelar cualquier reserva, usuario solo las suyas
        $usuario_id = isAdmin() ? null : $_SESSION['usuario_id'];
        $resultado = $this->reservaModel->cancelar($id, $usuario_id);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('usuario/mis-reservas');
    }
}