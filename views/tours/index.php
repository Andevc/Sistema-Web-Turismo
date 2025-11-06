<?php
$titulo = 'Tours Disponibles';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="page-header">
            <h2 class="page-title">Tours en Coroico</h2>
            <?php if (isAdmin()): ?>
            <a href="<?php echo url('tours/crear'); ?>" class="btn-primary">➕ Agregar Tour</a>
            <?php endif; ?>
        </div>
        
        <!-- Filtros de Búsqueda -->
        <div class="filters-card">
            <form method="GET" action="<?php echo url('tours'); ?>" class="filters-form">
                <div class="filter-group">
                    <label for="busqueda">Buscar</label>
                    <input type="text" id="busqueda" name="busqueda" class="form-control" 
                           placeholder="Nombre o descripción..."
                           value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="precio_min">Precio Mínimo</label>
                    <input type="number" id="precio_min" name="precio_min" class="form-control" 
                           placeholder="0" min="0" step="0.01"
                           value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="precio_max">Precio Máximo</label>
                    <input type="number" id="precio_max" name="precio_max" class="form-control" 
                           placeholder="1000" min="0" step="0.01"
                           value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="orden">Ordenar por</label>
                    <select id="orden" name="orden" class="form-control">
                        <option value="nombre_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'nombre_asc') ? 'selected' : ''; ?>>Nombre A-Z</option>
                        <option value="nombre_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'nombre_desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                        <option value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'precio_asc') ? 'selected' : ''; ?>>Precio Menor</option>
                        <option value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'precio_desc') ? 'selected' : ''; ?>>Precio Mayor</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-primary">🔍 Buscar</button>
                    <a href="<?php echo url('tours'); ?>" class="btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
        
        <!-- Resultados -->
        <?php if (!empty($tours)): ?>
        <div class="results-info">
            <p>Se encontraron <strong><?php echo count($tours); ?></strong> tours</p>
        </div>
        
        <div class="card-grid">
            <?php foreach ($tours as $tour): ?>
            <div class="card">
                <div class="card-image" style="background: linear-gradient(135deg, #87ceeb 0%, #2d5016 100%);">
                    <span class="card-badge">Tour</span>
                </div>
                
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($tour['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($tour['descripcion'], 0, 120)) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span>👥 Cupo: <?php echo $tour['cupo_maximo']; ?> personas</span>
                        <span class="price"><?php echo formatearPrecio($tour['precio']); ?></span>
                    </div>
                    
                    <div class="card-actions">
                        <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-primary btn-sm">Ver Detalles</a>
                        
                        <?php if (isAdmin()): ?>
                        <a href="<?php echo url('tours/editar?id=' . $tour['id_tour']); ?>" class="btn-sm btn-warning">Editar</a>
                        <button onclick="eliminarTour(<?php echo $tour['id_tour']; ?>)" class="btn-sm btn-danger">Eliminar</button>
                        <?php elseif (isLoggedIn()): ?>
                        <a href="<?php echo url('reservas/crear?tour=' . $tour['id_tour']); ?>" class="btn-sm">Reservar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <h3>No se encontraron tours</h3>
            <p>Intenta con otros filtros de búsqueda</p>
            <a href="<?php echo url('tours'); ?>" class="btn-primary">Ver Todos</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (isAdmin()): ?>
<script>
function eliminarTour(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este tour?')) {
        window.location.href = '<?php echo url('tours/eliminar?id='); ?>' + id;
    }
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>