<?php
$titulo = 'Editar Tour';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-back">← Volver</a>
            
            <h2>Editar Tour</h2>
            
            <form method="POST" action="<?php echo url('tours/actualizar'); ?>" class="form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $tour['id_tour']; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre del Tour *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" 
                           value="<?php echo htmlspecialchars($tour['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción Completa *</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="6" required><?php echo htmlspecialchars($tour['descripcion']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="precio">Precio por Persona (Bs.) *</label>
                        <input type="number" id="precio" name="precio" class="form-control" 
                               min="0" step="0.01" value="<?php echo $tour['precio']; ?>" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="cupo_maximo">Cupo Máximo de Personas *</label>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" class="form-control" 
                               min="1" value="<?php echo $tour['cupo_maximo']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="imagen_tour">Cambiar Imagen del Tour</label>
                    <?php if (!empty($tour['imagen_tour']) && $tour['imagen_tour'] !== 'default.jpg'): ?>
                    <div class="current-image">
                        <img src="<?php echo url('public/uploads/tours/' . $tour['imagen_tour']); ?>" alt="Imagen actual" style="max-width: 200px; margin-bottom: 1rem; border-radius: 8px;">
                        <p><small>Imagen actual</small></p>
                    </div>
                    <?php endif; ?>
                    <input type="file" id="imagen_tour" name="imagen_tour" class="form-control" accept="image/*">
                    <small class="form-text">Dejar vacío si no deseas cambiar la imagen</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Tour</button>
                    <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>