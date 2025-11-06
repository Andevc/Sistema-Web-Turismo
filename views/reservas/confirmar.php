<?php
$titulo = 'Reserva Confirmada';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="confirmation-page">
            <div class="confirmation-icon">✅</div>
            <h2>¡Reserva Confirmada!</h2>
            <p>Tu reserva se ha realizado exitosamente</p>
            
            <div class="confirmation-details">
                <h3>Detalles de tu Reserva</h3>
                
                <div class="detail-item">
                    <strong>Número de Reserva:</strong>
                    <span>#<?php echo str_pad($reserva['id_reserva'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                
                <div class="detail-item">
                    <strong>Tour:</strong>
                    <span><?php echo htmlspecialchars($reserva['tour_nombre']); ?></span>
                </div>
                
                <div class="detail-item">
                    <strong>Fecha del Tour:</strong>
                    <span><?php echo formatearFecha($reserva['fecha_tour']); ?></span>
                </div>
                
                <div class="detail-item">
                    <strong>Cantidad de Personas:</strong>
                    <span><?php echo $reserva['cantidad_personas']; ?> persona(s)</span>
                </div>
                
                <div class="detail-item">
                    <strong>Fecha de Reserva:</strong>
                    <span><?php echo formatearFecha($reserva['fecha_reserva'], true); ?></span>
                </div>
                
                <hr>
                
                <div class="detail-item detail-total">
                    <strong>Total Pagado:</strong>
                    <span class="price"><?php echo formatearPrecio($reserva['precio_total']); ?></span>
                </div>
            </div>
            
            <div class="confirmation-actions">
                <a href="<?php echo url('reservas/detalle?id=' . $reserva['id_reserva']); ?>" class="btn-primary">
                    Ver Detalles de la Reserva
                </a>
                <a href="<?php echo url('usuario/mis-reservas'); ?>" class="btn-secondary">
                    Ir a Mis Reservas
                </a>
                <a href="<?php echo url('tours'); ?>" class="btn-secondary">
                    Ver Más Tours
                </a>
            </div>
            
            <div class="confirmation-info">
                <p>📧 Te hemos enviado un correo de confirmación a <strong><?php echo htmlspecialchars($reserva['usuario_email']); ?></strong></p>
                <p>💡 Tip: Guarda el número de reserva para futuras referencias</p>
            </div>
        </div>
    </div>
</section>

<script>
// Limpiar localStorage después de confirmar
localStorage.removeItem('carritoReserva');
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>