<?php
$titulo = 'Crear Tour';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-page">
            <a href="<?php echo url('tours'); ?>" class="btn-back">← Volver</a>
            
            <h2>Agregar Nuevo Tour</h2>
            
            <form method="POST" action="<?php echo url('tours/guardar'); ?>" class="form">
                <div class="form-group">
                    <label for="nombre">Nombre del Tour *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción Completa *</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="6" required></textarea>
                    <small class="form-text">Incluye qué está incluido en el tour, itinerario, etc.</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="precio">Precio por Persona (Bs.) *</label>
                        <input type="number" id="precio" name="precio" class="form-control" 
                               min="0" step="0.01" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="cupo_maximo">Cupo Máximo de Personas *</label>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" class="form-control" 
                               min="1" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Guardar Tour</button>
                    <a href="<?php echo url('tours'); ?>" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>