<?php
/**
 * Controlador de Usuario
 * Maneja login, registro, perfil y mis reservas
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Reserva.php';

class UsuarioController {
    
    private $usuarioModel;
    private $reservaModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->reservaModel = new Reserva();
    }

    /**
     * Muestra el formulario de login
     */
    public function login() {
        // Si ya está logueado, redirigir
        if (isLoggedIn()) {
            redirect('');
        }
        
        require_once __DIR__ . '/../views/usuario/login.php';
    }

    /**
     * Procesa el login
     */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuario/login');
        }

        $email = sanitize($_POST['email'] ?? '');
        $password = sanitize($_POST['password'] ?? '');

        $resultado = $this->usuarioModel->autenticar($email, $password);

        if ($resultado['success']) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
            $_SESSION['nombre_completo'] = $resultado['usuario']['nombre_completo'];
            $_SESSION['email'] = $resultado['usuario']['email'];
            $_SESSION['rol'] = $resultado['usuario']['rol'];

            setFlashMessage('Bienvenido ' . $resultado['usuario']['nombre_completo'], 'success');
            
            // Redirigir según rol
            if ($resultado['usuario']['rol'] === ROL_ADMIN) {
                redirect('admin/dashboard');
            } else {
                redirect('');
            }
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('usuario/login');
        }
    }

    /**
     * Muestra el formulario de registro
     */
    public function registro() {
        // Si ya está logueado, redirigir
        if (isLoggedIn()) {
            redirect('');
        }
        
        require_once __DIR__ . '/../views/usuario/registro.php';
    }

    /**
     * Procesa el registro
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuario/registro');
        }

        $data = [
            'nombre_completo' => sanitize($_POST['nombre_completo'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'password' => sanitize($_POST['password'] ?? ''),
            'rol' => 'turista'
        ];

        // Verificar que las contraseñas coincidan
        $password_confirm = sanitize($_POST['password_confirm'] ?? '');
        if ($data['password'] !== $password_confirm) {
            setFlashMessage('Las contraseñas no coinciden', 'error');
            redirect('usuario/registro');
        }

        $resultado = $this->usuarioModel->crear($data);

        if ($resultado['success']) {
            setFlashMessage('Registro exitoso. Por favor inicia sesión.', 'success');
            redirect('usuario/login');
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('usuario/registro');
        }
    }

    /**
     * Muestra el perfil del usuario
     */
    public function perfil() {
        if (!isLoggedIn()) {
            setFlashMessage('Debes iniciar sesión', 'error');
            redirect('usuario/login');
        }

        $usuario = $this->usuarioModel->getById($_SESSION['usuario_id']);
        
        if (!$usuario) {
            setFlashMessage('Usuario no encontrado', 'error');
            redirect('');
        }

        require_once __DIR__ . '/../views/usuario/perfil.php';
    }

    /**
     * Actualiza el perfil del usuario
     */
    public function actualizar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuario/perfil');
        }

        $data = [
            'nombre_completo' => sanitize($_POST['nombre_completo'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
        ];

        // Si se proporciona nueva contraseña
        if (!empty($_POST['password'])) {
            $password_confirm = sanitize($_POST['password_confirm'] ?? '');
            if ($_POST['password'] !== $password_confirm) {
                setFlashMessage('Las contraseñas no coinciden', 'error');
                redirect('usuario/perfil');
            }
            $data['password'] = sanitize($_POST['password']);
        }

        $resultado = $this->usuarioModel->actualizar($_SESSION['usuario_id'], $data);

        if ($resultado['success']) {
            // Actualizar datos en sesión
            $_SESSION['nombre_completo'] = $data['nombre_completo'];
            $_SESSION['email'] = $data['email'];
            
            setFlashMessage($resultado['message'], 'success');
        } else {
            setFlashMessage($resultado['message'], 'error');
        }

        redirect('usuario/perfil');
    }

    /**
     * Muestra las reservas del usuario
     */
    public function misReservas() {
        if (!isLoggedIn()) {
            setFlashMessage('Debes iniciar sesión', 'error');
            redirect('usuario/login');
        }

        $reservasActivas = $this->reservaModel->getActivasPorUsuario($_SESSION['usuario_id']);
        $reservasPasadas = $this->reservaModel->getPasadasPorUsuario($_SESSION['usuario_id']);

        require_once __DIR__ . '/../views/usuario/mis_reservas.php';
    }

    /**
     * Elimina la cuenta del usuario
     */
    public function eliminar() {
        if (!isLoggedIn()) {
            redirect('usuario/login');
        }

        $resultado = $this->usuarioModel->eliminar($_SESSION['usuario_id']);

        if ($resultado['success']) {
            // Cerrar sesión
            session_destroy();
            setFlashMessage('Tu cuenta ha sido eliminada', 'success');
            redirect('');
        } else {
            setFlashMessage($resultado['message'], 'error');
            redirect('usuario/perfil');
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        session_destroy();
        setFlashMessage('Sesión cerrada exitosamente', 'success');
        redirect('');
    }
}