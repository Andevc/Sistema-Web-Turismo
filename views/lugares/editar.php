<?php
$titulo = 'Editar Lugar Turístico';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('lugares/detalle?id=' . $lugar['id_lugar']); ?>" class="btn-back">← Volver</a>
            
            <h2>Editar Lugar Turístico</h2>
            
            <form method="POST" action="<?php echo url('lugares/actualizar'); ?>" class="form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $lugar['id_lugar']; ?>">
                
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="nombre">Nombre del Lugar *</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($lugar['nombre']); ?>" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <?php foreach ($categorias as $key => $nombre): ?>
                            <option value="<?php echo $key; ?>" <?php echo $lugar['categoria'] == $key ? 'selected' : ''; ?>>
                                <?php echo $nombre; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="5" required><?php echo htmlspecialchars($lugar['descripcion']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección/Ubicación</label>
                    <input type="text" id="direccion" name="direccion" class="form-control" 
                           value="<?php echo htmlspecialchars($lugar['direccion']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="precio_entrada">Precio de Entrada (Bs.) *</label>
                        <input type="number" id="precio_entrada" name="precio_entrada" class="form-control" 
                               min="0" step="0.01" value="<?php echo $lugar['precio_entrada']; ?>" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="horario_apertura">Horario de Apertura</label>
                        <input type="time" id="horario_apertura" name="horario_apertura" class="form-control" 
                               value="<?php echo $lugar['horario_apertura']; ?>">
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="horario_cierre">Horario de Cierre</label>
                        <input type="time" id="horario_cierre" name="horario_cierre" class="form-control" 
                               value="<?php echo $lugar['horario_cierre']; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="imagen_lugar">Cambiar Imagen del Lugar</label>
                    <?php if (!empty($lugar['imagen_lugar']) && $lugar['imagen_lugar'] !== 'default.jpg'): ?>
                    <div class="current-image">
                        <img src="<?php echo url('public/uploads/lugares/' . $lugar['imagen_lugar']); ?>" alt="Imagen actual" style="max-width: 200px; margin-bottom: 1rem; border-radius: 8px;">
                        <p><small>Imagen actual</small></p>
                    </div>
                    <?php endif; ?>
                    <input type="file" id="imagen_lugar" name="imagen_lugar" class="form-control" accept="image/*">
                    <small class="form-text">Dejar vacío si no deseas cambiar la imagen</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Lugar</button>
                    <a href="<?php echo url('lugares/detalle?id=' . $lugar['id_lugar']); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>