<?php
$titulo = 'Editar Reserva';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('reservas/detalle?id=' . $reserva['id_reserva']); ?>" class="btn-back">← Volver</a>
            
            <h2>Modificar Reserva</h2>
            
            <div class="tour-summary">
                <h3><?php echo htmlspecialchars($reserva['tour_nombre']); ?></h3>
                <div class="tour-price">
                    <strong>Precio por persona:</strong> 
                    <span class="price"><?php echo formatearPrecio($reserva['tour_precio']); ?></span>
                </div>
            </div>
            
            <form method="POST" action="<?php echo url('reservas/actualizar'); ?>" class="form" id="formEditarReserva">
                <input type="hidden" name="id" value="<?php echo $reserva['id_reserva']; ?>">
                
                <div class="form-group">
                    <label for="fecha_tour">Nueva Fecha del Tour *</label>
                    <input type="date" id="fecha_tour" name="fecha_tour" class="form-control" 
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" 
                           value="<?php echo $reserva['fecha_tour']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="cantidad_personas">Cantidad de Personas *</label>
                    <input type="number" id="cantidad_personas" name="cantidad_personas" 
                           class="form-control" min="1" 
                           value="<?php echo $reserva['cantidad_personas']; ?>" required>
                </div>
                
                <div class="reservation-summary">
                    <h4>Resumen Actualizado</h4>
                    <div class="summary-item">
                        <span>Precio unitario:</span>
                        <strong><?php echo formatearPrecio($reserva['tour_precio']); ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Cantidad de personas:</span>
                        <strong id="personasMostrar"><?php echo $reserva['cantidad_personas']; ?></strong>
                    </div>
                    <hr>
                    <div class="summary-item summary-total">
                        <span>Nuevo Total:</span>
                        <strong class="price" id="totalMostrar"><?php echo formatearPrecio($reserva['precio_total']); ?></strong>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                    <a href="<?php echo url('reservas/detalle?id=' . $reserva['id_reserva']); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
const precioUnitario = <?php echo $reserva['tour_precio']; ?>;
const inputPersonas = document.getElementById('cantidad_personas');
const personasMostrar = document.getElementById('personasMostrar');
const totalMostrar = document.getElementById('totalMostrar');

inputPersonas.addEventListener('input', function() {
    const cantidad = parseInt(this.value) || 1;
    const total = cantidad * precioUnitario;
    
    personasMostrar.textContent = cantidad;
    totalMostrar.textContent = 'Bs. ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>