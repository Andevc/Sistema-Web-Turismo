<?php
$titulo = 'Iniciar Sesión';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Iniciar Sesión</h2>
                <p>Accede a tu cuenta de <?php echo SITE_NAME; ?></p>
            </div>
            
            <form method="POST" action="<?php echo url('usuario/autenticar'); ?>" class="form">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn-primary btn-block">Iniciar Sesión</button>
            </form>
            
            <div class="form-footer">
                <p>¿No tienes una cuenta? <a href="<?php echo url('usuario/registro'); ?>">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>