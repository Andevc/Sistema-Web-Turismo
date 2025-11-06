<?php
$titulo = 'Registro';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Crear Cuenta</h2>
                <p>Únete a <?php echo SITE_NAME; ?> y descubre Coroico</p>
            </div>
            
            <form method="POST" action="<?php echo url('usuario/guardar'); ?>" class="form">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <small class="form-text">Mínimo 6 caracteres</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmar Contraseña</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                </div>
                
                <button type="submit" class="btn-primary btn-block">Registrarse</button>
            </form>
            
            <div class="form-footer">
                <p>¿Ya tienes una cuenta? <a href="<?php echo url('usuario/login'); ?>">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>