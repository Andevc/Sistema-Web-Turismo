<?php
$titulo = 'Agregar Comentario';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('lugares/detalle?id=' . $id_lugar); ?>" class="btn-back">← Volver al Lugar</a>
            
            <h2>Deja tu Opinión</h2>
            
            <?php if (isset($lugar)): ?>
            <div class="comentario-context">
                <p>Estás comentando sobre: <strong><?php echo htmlspecialchars($lugar['nombre']); ?></strong></p>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo url('comentarios/crear'); ?>" class="form">
                <input type="hidden" name="id_lugar" value="<?php echo $id_lugar; ?>">
                
                <div class="form-group">
                    <label for="calificacion">Calificación *</label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="calificacion" value="5" required>
                        <label for="star5" title="5 estrellas">★</label>
                        
                        <input type="radio" id="star4" name="calificacion" value="4">
                        <label for="star4" title="4 estrellas">★</label>
                        
                        <input type="radio" id="star3" name="calificacion" value="3">
                        <label for="star3" title="3 estrellas">★</label>
                        
                        <input type="radio" id="star2" name="calificacion" value="2">
                        <label for="star2" title="2 estrellas">★</label>
                        
                        <input type="radio" id="star1" name="calificacion" value="1">
                        <label for="star1" title="1 estrella">★</label>
                    </div>
                    <small class="form-text">Haz clic en las estrellas para calificar</small>
                </div>
                
                <div class="form-group">
                    <label for="comentario">Tu Comentario *</label>
                    <textarea id="comentario" name="comentario" class="form-control" rows="6" 
                              placeholder="Cuéntanos sobre tu experiencia en este lugar..." required></textarea>
                    <small class="form-text">Comparte detalles que puedan ayudar a otros visitantes</small>
                </div>
                
                <div class="form-info">
                    <p>💡 <strong>Consejos para un buen comentario:</strong></p>
                    <ul>
                        <li>Sé específico sobre lo que te gustó o no te gustó</li>
                        <li>Menciona la época del año en que visitaste</li>
                        <li>Comparte tips útiles para otros viajeros</li>
                        <li>Sé respetuoso y constructivo</li>
                    </ul>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Publicar Comentario</button>
                    <a href="<?php echo url('lugares/detalle?id=' . $id_lugar); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Efecto visual en las estrellas
const stars = document.querySelectorAll('.rating-input input[type="radio"]');
const labels = document.querySelectorAll('.rating-input label');

labels.forEach((label, index) => {
    label.addEventListener('mouseover', function() {
        for (let i = labels.length - 1; i >= labels.length - 1 - index; i--) {
            labels[i].style.color = '#ffc107';
        }
    });
    
    label.addEventListener('mouseout', function() {
        labels.forEach(l => l.style.color = '');
        // Mantener color en la seleccionada
        const checked = document.querySelector('.rating-input input[type="radio"]:checked');
        if (checked) {
            const checkedIndex = Array.from(stars).indexOf(checked);
            for (let i = labels.length - 1; i >= labels.length - 1 - checkedIndex; i--) {
                labels[i].style.color = '#ffc107';
            }
        }
    });
});

stars.forEach((star, index) => {
    star.addEventListener('change', function() {
        for (let i = labels.length - 1; i >= labels.length - 1 - index; i--) {
            labels[i].style.color = '#ffc107';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>