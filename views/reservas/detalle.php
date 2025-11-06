<?php
$titulo = 'Detalle de Reserva';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="detalle-page">
            <a href="<?php echo url('usuario/mis-reservas'); ?>" class="btn-back">← Volver a Mis Reservas</a>
            
            <h2>Detalle de Reserva #<?php echo str_pad($reserva['id_reserva'], 6, '0', STR_PAD_LEFT); ?></h2>
            
            <div class="reserva-detalle-card">
                <div class="reserva-header">
                    <h3><?php echo htmlspecialchars($reserva['tour_nombre']); ?></h3>
                    <?php
                    $fecha_tour = strtotime($reserva['fecha_tour']);
                    $hoy = strtotime('today');
                    $estado = $fecha_tour >= $hoy ? 'Próxima' : 'Realizada';
                    $estadoClass = $fecha_tour >= $hoy ? 'badge-success' : 'badge-secondary';
                    ?>
                    <span class="badge <?php echo $estadoClass; ?>"><?php echo $estado; ?></span>
                </div>
                
                <div class="reserva-info">
                    <div class="info-section">
                        <h4>Información del Tour</h4>
                        <p><?php echo nl2br(htmlspecialchars($reserva['tour_descripcion'])); ?></p>
                    </div>
                    
                    <div class="info-section">
                        <h4>Detalles de la Reserva</h4>
                        
                        <div class="info-item">
                            <strong>Fecha del Tour:</strong>
                            <span><?php echo formatearFecha($reserva['fecha_tour']); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <strong>Cantidad de Personas:</strong>
                            <span><?php echo $reserva['cantidad_personas']; ?> persona(s)</span>
                        </div>
                        
                        <div class="info-item">
                            <strong>Precio por Persona:</strong>
                            <span><?php echo formatearPrecio($reserva['tour_precio']); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <strong>Total Pagado:</strong>
                            <span class="price"><?php echo formatearPrecio($reserva['precio_total']); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <strong>Fecha de Reserva:</strong>
                            <span><?php echo formatearFecha($reserva['fecha_reserva'], true); ?></span>
                        </div>
                    </div>
                    
                    <?php if (isAdmin()): ?>
                    <div class="info-section">
                        <h4>Información del Cliente</h4>
                        
                        <div class="info-item">
                            <strong>Nombre:</strong>
                            <span><?php echo htmlspecialchars($reserva['usuario_nombre']); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <strong>Email:</strong>
                            <span><?php echo htmlspecialchars($reserva['usuario_email']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($fecha_tour >= $hoy): ?>
                <div class="reserva-actions">
                    <a href="<?php echo url('reservas/editar?id=' . $reserva['id_reserva']); ?>" class="btn-primary">
                        Modificar Reserva
                    </a>
                    <button onclick="cancelarReserva()" class="btn-danger">
                        Cancelar Reserva
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function cancelarReserva() {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        window.location.href = '<?php echo url('reservas/cancelar?id=' . $reserva['id_reserva']); ?>';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>