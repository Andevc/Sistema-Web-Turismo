<?php
$titulo = 'Hacer Reserva';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-back">← Volver al Tour</a>
            
            <h2>Reservar Tour</h2>
            
            <div class="tour-summary">
                <h3><?php echo htmlspecialchars($tour['nombre']); ?></h3>
                <p><?php echo htmlspecialchars(substr($tour['descripcion'], 0, 200)) . '...'; ?></p>
                <div class="tour-price">
                    <strong>Precio por persona:</strong> 
                    <span class="price"><?php echo formatearPrecio($tour['precio']); ?></span>
                </div>
            </div>
            
            <form method="POST" action="<?php echo url('reservas/guardar'); ?>" class="form" id="formReserva">
                <input type="hidden" name="id_tour" value="<?php echo $tour['id_tour']; ?>">
                
                <div class="form-group">
                    <label for="fecha_tour">Fecha del Tour *</label>
                    <input type="date" id="fecha_tour" name="fecha_tour" class="form-control" 
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    <small class="form-text">Selecciona una fecha futura</small>
                </div>
                
                <div class="form-group">
                    <label for="cantidad_personas">Cantidad de Personas *</label>
                    <input type="number" id="cantidad_personas" name="cantidad_personas" 
                           class="form-control" min="1" max="<?php echo $tour['cupo_maximo']; ?>" 
                           value="1" required>
                    <small class="form-text">Máximo <?php echo $tour['cupo_maximo']; ?> personas</small>
                </div>
                
                <div class="reservation-summary">
                    <h4>Resumen de la Reserva</h4>
                    <div class="summary-item">
                        <span>Tour:</span>
                        <strong><?php echo htmlspecialchars($tour['nombre']); ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Precio unitario:</span>
                        <strong><?php echo formatearPrecio($tour['precio']); ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Cantidad de personas:</span>
                        <strong id="personasMostrar">1</strong>
                    </div>
                    <hr>
                    <div class="summary-item summary-total">
                        <span>Total a pagar:</span>
                        <strong class="price" id="totalMostrar"><?php echo formatearPrecio($tour['precio']); ?></strong>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-lg">Confirmar Reserva</button>
                    <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Calcular precio total dinámicamente
const precioUnitario = <?php echo $tour['precio']; ?>;
const inputPersonas = document.getElementById('cantidad_personas');
const personasMostrar = document.getElementById('personasMostrar');
const totalMostrar = document.getElementById('totalMostrar');

inputPersonas.addEventListener('input', function() {
    const cantidad = parseInt(this.value) || 1;
    const total = cantidad * precioUnitario;
    
    personasMostrar.textContent = cantidad;
    totalMostrar.textContent = 'Bs. ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    
    // Guardar en localStorage para referencia
    localStorage.setItem('carritoReserva', JSON.stringify({
        tour_id: <?php echo $tour['id_tour']; ?>,
        tour_nombre: '<?php echo addslashes($tour['nombre']); ?>',
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        total: total
    }));
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>