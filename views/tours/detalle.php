<?php
$titulo = $tour['nombre'];
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="detalle-header">
            <a href="<?php echo url('tours'); ?>" class="btn-back">← Volver a Tours</a>
            <?php if (isAdmin()): ?>
            <div>
                <a href="<?php echo url('tours/editar?id=' . $tour['id_tour']); ?>" class="btn-warning">Editar</a>
                <button onclick="eliminarTour()" class="btn-danger">Eliminar</button>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="detalle-content">
            <div class="detalle-image" style="background: linear-gradient(135deg, #87ceeb 0%, #2d5016 100%);">
                <span class="detalle-category">Tour</span>
            </div>
            
            <div class="detalle-info">
                <h1><?php echo htmlspecialchars($tour['nombre']); ?></h1>
                
                <div class="detalle-meta">
                    <div class="meta-item">
                        <strong>Precio por persona:</strong>
                        <span class="price"><?php echo formatearPrecio($tour['precio']); ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <strong>Cupo máximo:</strong>
                        <span><?php echo $tour['cupo_maximo']; ?> personas</span>
                    </div>
                    
                    <div class="meta-item">
                        <strong>Total de reservas:</strong>
                        <span><?php echo $tour['total_reservas'] ?? 0; ?> reservas</span>
                    </div>
                    
                    <?php if (isset($disponibilidad)): ?>
                    <div class="meta-item">
                        <strong>Disponibilidad (ejemplo):</strong>
                        <span class="<?php echo $disponibilidad['disponible'] ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $disponibilidad['cupos_disponibles']; ?> cupos disponibles
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="detalle-description">
                    <h3>Descripción del Tour</h3>
                    <p><?php echo nl2br(htmlspecialchars($tour['descripcion'])); ?></p>
                </div>
                
                <?php if (isLoggedIn() && !isAdmin()): ?>
                <div class="detalle-actions">
                    <a href="<?php echo url('reservas/crear?tour=' . $tour['id_tour']); ?>" class="btn-primary btn-lg">
                        📅 Reservar Ahora
                    </a>
                </div>
                <?php elseif (!isLoggedIn()): ?>
                <div class="alert alert-info">
                    <p>Para reservar este tour, debes <a href="<?php echo url('usuario/login'); ?>">iniciar sesión</a> o <a href="<?php echo url('usuario/registro'); ?>">crear una cuenta</a>.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Estadísticas del Tour -->
        <?php if (isAdmin() && $tour['total_reservas'] > 0): ?>
        <div class="tour-stats">
            <h3>Estadísticas del Tour</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total Reservas:</span>
                    <span class="stat-value"><?php echo $tour['total_reservas']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Personas:</span>
                    <span class="stat-value"><?php echo $tour['total_personas'] ?? 0; ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (isAdmin()): ?>
<script>
function eliminarTour() {
    if (confirm('¿Estás seguro de que deseas eliminar este tour?')) {
        window.location.href = '<?php echo url('tours/eliminar?id=' . $tour['id_tour']); ?>';
    }
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>