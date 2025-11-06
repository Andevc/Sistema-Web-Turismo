<?php
$titulo = 'Mi Perfil';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="page-title">Mi Perfil</h2>
        
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-avatar">
                    <?php
                    $iniciales = '';
                    $palabras = explode(' ', $usuario['nombre_completo']);
                    foreach (array_slice($palabras, 0, 2) as $palabra) {
                        $iniciales .= strtoupper(substr($palabra, 0, 1));
                    }
                    ?>
                    <span><?php echo $iniciales; ?></span>
                </div>
                <div class="profile-details">
                    <h3><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h3>
                    <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                    <span class="badge <?php echo $usuario['rol'] === 'admin' ? 'badge-admin' : 'badge-turista'; ?>">
                        <?php echo ucfirst($usuario['rol']); ?>
                    </span>
                </div>
            </div>
            
            <form method="POST" action="<?php echo url('usuario/actualizar'); ?>" class="form">
                <h3>Actualizar Información</h3>
                
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" 
                           value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>
                
                <hr>
                
                <h3>Cambiar Contraseña</h3>
                <p class="form-text">Deja en blanco si no deseas cambiar la contraseña</p>
                
                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                    <a href="<?php echo url(''); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
            
            <div class="danger-zone">
                <h3>Zona de Peligro</h3>
                <p>Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, ten cuidado.</p>
                <button onclick="confirmarEliminacion()" class="btn-danger">Eliminar Cuenta</button>
            </div>
        </div>
    </div>
</section>

<script>
function confirmarEliminacion() {
    if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
        window.location.href = '<?php echo url('usuario/eliminar'); ?>';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>