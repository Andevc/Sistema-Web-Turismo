<?php
$titulo = 'Crear Lugar Turístico';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('lugares'); ?>" class="btn-back">← Volver</a>
            
            <h2>Agregar Nuevo Lugar Turístico</h2>
            
            <form method="POST" action="<?php echo url('lugares/guardar'); ?>" class="form">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="nombre">Nombre del Lugar *</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <option value="">Selecciona...</option>
                            <?php foreach ($categorias as $key => $nombre): ?>
                            <option value="<?php echo $key; ?>"><?php echo $nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección/Ubicación</label>
                    <input type="text" id="direccion" name="direccion" class="form-control" 
                           placeholder="Ej: Comunidad de Vagantes, 8 km de Coroico">
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="precio_entrada">Precio de Entrada (Bs.) *</label>
                        <input type="number" id="precio_entrada" name="precio_entrada" class="form-control" 
                               min="0" step="0.01" value="0" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="horario_apertura">Horario de Apertura</label>
                        <input type="time" id="horario_apertura" name="horario_apertura" class="form-control">
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="horario_cierre">Horario de Cierre</label>
                        <input type="time" id="horario_cierre" name="horario_cierre" class="form-control">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Guardar Lugar</button>
                    <a href="<?php echo url('lugares'); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>