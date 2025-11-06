<?php
$titulo = 'Dashboard Administrativo';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="page-title">Panel de Administración</h2>
        
        <!-- Estadísticas Generales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3><?php echo $totalUsuarios; ?></h3>
                    <p>Total Usuarios</p>
                    <small><?php echo $totalTuristas; ?> turistas</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🏔️</div>
                <div class="stat-content">
                    <h3><?php echo $totalLugares; ?></h3>
                    <p>Lugares Turísticos</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🎒</div>
                <div class="stat-content">
                    <h3><?php echo $totalTours; ?></h3>
                    <p>Tours Disponibles</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <h3><?php echo $estadisticasReservas['total_reservas'] ?? 0; ?></h3>
                    <p>Total Reservas</p>
                    <small><?php echo $estadisticasReservas['total_personas'] ?? 0; ?> personas</small>
                </div>
            </div>
            
            <div class="stat-card highlight">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <h3><?php echo formatearPrecio($ingresosTotales); ?></h3>
                    <p>Ingresos Totales</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💬</div>
                <div class="stat-content">
                    <h3><?php echo $estadisticasComentarios['total_comentarios'] ?? 0; ?></h3>
                    <p>Comentarios</p>
                    <small>Promedio: <?php echo number_format($estadisticasComentarios['promedio_general'] ?? 0, 1); ?> ★</small>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="quick-actions">
            <h3>Acciones Rápidas</h3>
            <div class="action-buttons">
                <a href="<?php echo url('lugares/crear'); ?>" class="action-btn">➕ Agregar Lugar</a>
                <a href="<?php echo url('tours/crear'); ?>" class="action-btn">➕ Agregar Tour</a>
                <a href="<?php echo url('lugares'); ?>" class="action-btn">📋 Ver Lugares</a>
                <a href="<?php echo url('tours'); ?>" class="action-btn">📋 Ver Tours</a>
            </div>
        </div>
        
        <!-- Tours Más Reservados -->
        <div class="dashboard-section">
            <h3>Tours Más Reservados</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Reservas</th>
                            <th>Cupo Máximo</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($toursMasReservados as $tour): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($tour['nombre']); ?></strong></td>
                            <td><?php echo $tour['total_reservas'] ?? 0; ?></td>
                            <td><?php echo $tour['cupo_maximo']; ?></td>
                            <td class="price"><?php echo formatearPrecio($tour['precio']); ?></td>
                            <td>
                                <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-sm">Ver</a>
                                <a href="<?php echo url('tours/editar?id=' . $tour['id_tour']); ?>" class="btn-sm btn-warning">Editar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Lugares Más Comentados -->
        <div class="dashboard-section">
            <h3>Lugares Más Comentados</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lugar</th>
                            <th>Categoría</th>
                            <th>Comentarios</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lugaresMasComentados as $lugar): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($lugar['nombre']); ?></strong></td>
                            <td><?php echo ucfirst($lugar['categoria']); ?></td>
                            <td><?php echo $lugar['total_comentarios'] ?? 0; ?></td>
                            <td class="price"><?php echo formatearPrecio($lugar['precio_entrada']); ?></td>
                            <td>
                                <a href="<?php echo url('lugares/detalle?id=' . $lugar['id_lugar']); ?>" class="btn-sm">Ver</a>
                                <a href="<?php echo url('lugares/editar?id=' . $lugar['id_lugar']); ?>" class="btn-sm btn-warning">Editar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Próximas Reservas -->
        <?php if (!empty($proximasReservas)): ?>
        <div class="dashboard-section">
            <h3>Próximas Reservas</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Tour</th>
                            <th>Fecha</th>
                            <th>Personas</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proximasReservas as $reserva): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['usuario_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['tour_nombre']); ?></td>
                            <td><?php echo formatearFecha($reserva['fecha_tour']); ?></td>
                            <td><?php echo $reserva['cantidad_personas']; ?></td>
                            <td class="price"><?php echo formatearPrecio($reserva['precio_total']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Estadísticas de Ingresos por Tour -->
        <div class="dashboard-section">
            <h3>Ingresos por Tour</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Reservas</th>
                            <th>Ingresos</th>
                            <th>Precio Base</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estadisticasIngresos as $stat): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($stat['nombre']); ?></strong></td>
                            <td><?php echo $stat['total_reservas']; ?></td>
                            <td class="price"><strong><?php echo formatearPrecio($stat['ingresos_totales']); ?></strong></td>
                            <td><?php echo formatearPrecio($stat['precio']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Lugares por Categoría -->
        <div class="dashboard-section">
            <h3>Distribución de Lugares por Categoría</h3>
            <div class="category-stats">
                <?php foreach ($lugaresPorCategoria as $cat): ?>
                <div class="category-stat-item">
                    <span class="category-name"><?php echo ucfirst($cat['categoria']); ?></span>
                    <span class="category-count"><?php echo $cat['total']; ?> lugares</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>