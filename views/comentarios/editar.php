<?php
$titulo = 'Editar Comentario';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('lugares/detalle?id=' . $comentario['id_lugar']); ?>" class="btn-back">← Volver al Lugar</a>
            
            <h2>Editar tu Comentario</h2>
            
            <div class="comentario-context">
                <p>Estás editando tu opinión sobre: <strong><?php echo htmlspecialchars($comentario['lugar_nombre']); ?></strong></p>
            </div>
            
            <form method="POST" action="<?php echo url('comentarios/actualizar'); ?>" class="form">
                <input type="hidden" name="id" value="<?php echo $comentario['id_comentario']; ?>">
                
                <div class="form-group">
                    <label for="calificacion">Calificación *</label>
                    <div class="rating-input">
                        <?php for($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?php echo $i; ?>" name="calificacion" value="<?php echo $i; ?>" 
                               <?php echo $comentario['calificacion'] == $i ? 'checked' : ''; ?> required>
                        <label for="star<?php echo $i; ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comentario">Tu Comentario *</label>
                    <textarea id="comentario" name="comentario" class="form-control" rows="5" required><?php echo htmlspecialchars($comentario['comentario']); ?></textarea>
                    <small class="form-text">Comparte tu experiencia de forma detallada</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Comentario</button>
                    <a href="<?php echo url('lugares/detalle?id=' . $comentario['id_lugar']); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>