<?php
$titulo = $lugar['nombre'];
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="detalle-header">
            <a href="<?php echo url('lugares'); ?>" class="btn-back">← Volver a Lugares</a>
            <?php if (isAdmin()): ?>
            <div>
                <a href="<?php echo url('lugares/editar?id=' . $lugar['id_lugar']); ?>" class="btn-warning">Editar</a>
                <button onclick="eliminarLugar()" class="btn-danger">Eliminar</button>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="detalle-content">
            <div class="detalle-image" style="background: linear-gradient(135deg, #2d5016 0%, #4a9d5f 100%);">
                <span class="detalle-category"><?php echo ucfirst($lugar['categoria']); ?></span>
            </div>
            
            <div class="detalle-info">
                <h1><?php echo htmlspecialchars($lugar['nombre']); ?></h1>
                
                <div class="detalle-rating">
                    <div class="rating-stars">
                        <?php 
                        $calificacion = round($estadisticas['promedio']);
                        for($i = 1; $i <= 5; $i++) {
                            echo $i <= $calificacion ? '★' : '☆';
                        }
                        ?>
                        <span><?php echo number_format($estadisticas['promedio'], 1); ?></span>
                    </div>
                    <span class="rating-count">(<?php echo $estadisticas['total_comentarios']; ?> opiniones)</span>
                </div>
                
                <div class="detalle-meta">
                    <div class="meta-item">
                        <strong>Precio de entrada:</strong>
                        <span class="price"><?php echo formatearPrecio($lugar['precio_entrada']); ?></span>
                    </div>
                    
                    <?php if (!empty($lugar['horario_apertura'])): ?>
                    <div class="meta-item">
                        <strong>Horario:</strong>
                        <span><?php echo substr($lugar['horario_apertura'], 0, 5); ?> - <?php echo substr($lugar['horario_cierre'], 0, 5); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($lugar['direccion'])): ?>
                    <div class="meta-item">
                        <strong>Ubicación:</strong>
                        <span>📍 <?php echo htmlspecialchars($lugar['direccion']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="detalle-description">
                    <h3>Descripción</h3>
                    <p><?php echo nl2br(htmlspecialchars($lugar['descripcion'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Sección de Comentarios -->
        <div class="comentarios-section">
            <h2>Opiniones de Visitantes</h2>
            
            <?php if (isLoggedIn() && !isAdmin()): ?>
            <div class="comentario-form-card">
                <h3>Deja tu opinión</h3>
                <form method="POST" action="<?php echo url('comentarios/crear'); ?>" class="form">
                    <input type="hidden" name="id_lugar" value="<?php echo $lugar['id_lugar']; ?>">
                    
                    <div class="form-group">
                        <label for="calificacion">Calificación</label>
                        <div class="rating-input">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?php echo $i; ?>" name="calificacion" value="<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comentario">Tu Comentario</label>
                        <textarea id="comentario" name="comentario" class="form-control" rows="4" 
                                  placeholder="Cuéntanos sobre tu experiencia..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Publicar Comentario</button>
                </form>
            </div>
            <?php endif; ?>
            
            <!-- Lista de Comentarios -->
            <?php if (!empty($comentarios)): ?>
            <div class="comentarios-lista">
                <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario-card">
                    <div class="comentario-header">
                        <div class="comentario-author">
                            <div class="author-avatar">
                                <?php
                                $palabras = explode(' ', $comentario['usuario_nombre']);
                                $iniciales = strtoupper(substr($palabras[0], 0, 1));
                                if (isset($palabras[1])) $iniciales .= strtoupper(substr($palabras[1], 0, 1));
                                echo $iniciales;
                                ?>
                            </div>
                            <div>
                                <strong><?php echo htmlspecialchars($comentario['usuario_nombre']); ?></strong>
                                <small><?php echo formatearFecha($comentario['fecha_comentario'], true); ?></small>
                            </div>
                        </div>
                        
                        <div class="comentario-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php echo $i <= $comentario['calificacion'] ? '★' : '☆'; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="comentario-content">
                        <p><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
                    </div>
                    
                    <?php if (isLoggedIn() && ($_SESSION['usuario_id'] == $comentario['id_usuario'] || isAdmin())): ?>
                    <div class="comentario-actions">
                        <a href="<?php echo url('comentarios/editar?id=' . $comentario['id_comentario']); ?>" class="btn-sm">Editar</a>
                        <button onclick="eliminarComentario(<?php echo $comentario['id_comentario']; ?>)" class="btn-sm btn-danger">Eliminar</button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-center">Aún no hay comentarios. ¡Sé el primero en opinar!</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
<?php if (isAdmin()): ?>
function eliminarLugar() {
    if (confirm('¿Estás seguro de que deseas eliminar este lugar?')) {
        window.location.href = '<?php echo url('lugares/eliminar?id=' . $lugar['id_lugar']); ?>';
    }
}
<?php endif; ?>

function eliminarComentario(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este comentario?')) {
        window.location.href = '<?php echo url('comentarios/eliminar?id='); ?>' + id;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>