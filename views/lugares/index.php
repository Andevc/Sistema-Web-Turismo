<?php
$titulo = 'Lugares Turísticos';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="page-header">
            <h2 class="page-title">Lugares Turísticos de Coroico</h2>
            <?php if (isAdmin()): ?>
            <a href="<?php echo url('lugares/crear'); ?>" class="btn-primary">➕ Agregar Lugar</a>
            <?php endif; ?>
        </div>
        
        <!-- Filtros de Búsqueda -->
        <div class="filters-card">
            <form method="GET" action="<?php echo url('lugares'); ?>" class="filters-form">
                <div class="filter-group">
                    <label for="busqueda">Buscar</label>
                    <input type="text" id="busqueda" name="busqueda" class="form-control" 
                           placeholder="Nombre o descripción..."
                           value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $key => $nombre): ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $nombre; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
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
                           placeholder="100" min="0" step="0.01"
                           value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-primary">🔍 Buscar</button>
                    <a href="<?php echo url('lugares'); ?>" class="btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
        
        <!-- Resultados -->
        <?php if (!empty($lugares)): ?>
        <div class="results-info">
            <p>Se encontraron <strong><?php echo count($lugares); ?></strong> lugares</p>
        </div>
        
        <div class="card-grid">
            <?php foreach ($lugares as $lugar): ?>
            <div class="card">
                <div class="card-image" style="background: linear-gradient(135deg, #2d5016 0%, #4a9d5f 100%);">
                    <span class="card-category"><?php echo ucfirst($lugar['categoria']); ?></span>
                </div>
                
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($lugar['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($lugar['descripcion'], 0, 120)) . '...'; ?></p>
                    
                    <?php if (!empty($lugar['direccion'])): ?>
                    <p class="card-location">📍 <?php echo htmlspecialchars($lugar['direccion']); ?></p>
                    <?php endif; ?>
                    
                    <div class="card-meta">
                        <span class="price"><?php echo formatearPrecio($lugar['precio_entrada']); ?></span>
                        <?php if (!empty($lugar['horario_apertura'])): ?>
                        <span>🕐 <?php echo substr($lugar['horario_apertura'], 0, 5); ?> - <?php echo substr($lugar['horario_cierre'], 0, 5); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-actions">
                        <a href="<?php echo url('lugares/detalle?id=' . $lugar['id_lugar']); ?>" class="btn-primary btn-sm">Ver Detalles</a>
                        
                        <?php if (isAdmin()): ?>
                        <a href="<?php echo url('lugares/editar?id=' . $lugar['id_lugar']); ?>" class="btn-sm btn-warning">Editar</a>
                        <button onclick="eliminarLugar(<?php echo $lugar['id_lugar']; ?>)" class="btn-sm btn-danger">Eliminar</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <h3>No se encontraron lugares</h3>
            <p>Intenta con otros filtros de búsqueda</p>
            <a href="<?php echo url('lugares'); ?>" class="btn-primary">Ver Todos</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (isAdmin()): ?>
<script>
function eliminarLugar(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este lugar turístico?')) {
        window.location.href = '<?php echo url('lugares/eliminar?id='); ?>' + id;
    }
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>