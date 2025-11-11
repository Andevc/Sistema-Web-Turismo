<?php
/**
 * Controlador de Comentarios
 * Maneja la creación, edición y eliminación de comentarios
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Comentario.php';
require_once __DIR__ . '/../models/LugarTuristico.php';

class ComentarioController {
    
    private $comentarioModel;
    private $lugarModel;

    public function __construct() {
        $this->comentarioModel = new Comentario();
        $this->lugarModel = new LugarTuristico();
    }

    /**
     * Guarda un nuevo comentario
     */
    public function crear() {
        if (!isLoggedIn()) {
            setFlashMessage('Debes iniciar sesión para comentar', 'error');
            redirect('usuario/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('lugares');
        }

        $id_lugar = isset($_POST['id_lugar']) ? (int)$_POST['id_lugar'] : null;
        
        if (!$id_lugar) {
            setFlashMessage('Lugar no especificado', 'error');
            redirect('lugares');
        }

        $data = [
            'id_usuario' => $_SESSION['usuario_id'],
            'id_lugar' => $id_lugar,
            'calificacion' => (int)($_POST['calificacion'] ?? 0),
            'comentario' => sanitize($_POST['comentario'] ?? '')
        ];

        $resultado = $this->comentarioModel->crear($data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('lugares/detalle?id=' . $id_lugar);
    }

    /**
     * Muestra el formulario para editar un comentario
     */
    public function editar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Comentario no encontrado', 'error');
            redirect('');
        }

        $comentario = $this->comentarioModel->getDetalleCompleto($id);

        // Verificar que el comentario pertenezca al usuario (o sea admin)
        if (!$comentario || (!isAdmin() && $comentario['id_usuario'] != $_SESSION['usuario_id'])) {
            setFlashMessage('No tienes permiso para editar este comentario', 'error');
            redirect('');
        }

        require_once __DIR__ . '/../views/comentarios/editar.php';
    }

    /**
     * Actualiza un comentario
     */
    public function actualizar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            setFlashMessage('Comentario no encontrado', 'error');
            redirect('');
        }

        // Verificar permisos
        $comentario = $this->comentarioModel->getById($id);

        if (!$comentario || (!isAdmin() && $comentario['id_usuario'] != $_SESSION['usuario_id'])) {
            setFlashMessage('No tienes permiso para editar este comentario', 'error');
            redirect('');
        }

        $data = [
            'calificacion' => (int)($_POST['calificacion'] ?? 0),
            'comentario' => sanitize($_POST['comentario'] ?? '')
        ];

        $resultado = $this->comentarioModel->actualizar($id, $data);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('lugares/detalle?id=' . $comentario['id_lugar']);
    }

    /**
     * Elimina un comentario
     */
    public function eliminar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('Comentario no encontrado', 'error');
            redirect('');
        }

        // Obtener el comentario para saber a qué lugar pertenece
        $comentario = $this->comentarioModel->getById($id);

        if (!$comentario) {
            setFlashMessage('Comentario no encontrado', 'error');
            redirect('');
        }

        // Admin puede eliminar cualquier comentario, usuario solo los suyos
        $usuario_id = isAdmin() ? null : $_SESSION['usuario_id'];
        $resultado = $this->comentarioModel->eliminar($id, $usuario_id);

        if ($resultado['success']) {
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('lugares/detalle?id=' . $comentario['id_lugar']);
    }
}