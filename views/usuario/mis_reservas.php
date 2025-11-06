<?php
$titulo = 'Mis Reservas';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="page-title">Mis Reservas</h2>
        
        <!-- Reservas Activas -->
        <div class="reservas-section">
            <h3>Próximas Reservas</h3>
            
            <?php if (!empty($reservasActivas)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Fecha</th>
                            <th>Personas</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasActivas as $reserva): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($reserva['tour_nombre']); ?></strong>
                            </td>
                            <td><?php echo formatearFecha($reserva['fecha_tour']); ?></td>
                            <td><?php echo $reserva['cantidad_personas']; ?> personas</td>
                            <td class="price"><?php echo formatearPrecio($reserva['precio_total']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo url('reservas/detalle?id=' . $reserva['id_reserva']); ?>" 
                                       class="btn-sm">Ver</a>
                                    <a href="<?php echo url('reservas/editar?id=' . $reserva['id_reserva']); ?>" 
                                       class="btn-sm btn-warning">Editar</a>
                                    <button onclick="cancelarReserva(<?php echo $reserva['id_reserva']; ?>)" 
                                            class="btn-sm btn-danger">Cancelar</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <p>No tienes reservas próximas</p>
                <a href="<?php echo url('tours'); ?>" class="btn-primary">Explorar Tours</a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Reservas Pasadas -->
        <?php if (!empty($reservasPasadas)): ?>
        <div class="reservas-section mt-3">
            <h3>Historial de Reservas</h3>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Fecha Realizada</th>
                            <th>Personas</th>
                            <th>Total Pagado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasPasadas as $reserva): ?>
                        <tr class="reserva-pasada">
                            <td><?php echo htmlspecialchars($reserva['tour_nombre']); ?></td>
                            <td><?php echo formatearFecha($reserva['fecha_tour']); ?></td>
                            <td><?php echo $reserva['cantidad_personas']; ?> personas</td>
                            <td class="price"><?php echo formatearPrecio($reserva['precio_total']); ?></td>
                            <td>
                                <a href="<?php echo url('reservas/detalle?id=' . $reserva['id_reserva']); ?>" 
                                   class="btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function cancelarReserva(id) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        window.location.href = '<?php echo url('reservas/cancelar?id='); ?>' + id;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>