<?php
$titulo = 'Inicio';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content hero-banner">
            <h1>Descubre Coroico</h1>
            <p>El paraíso natural de los Yungas bolivianos</p>
            <div class="hero-actions">
                <a href="<?php echo url('lugares'); ?>" class="btn-primary">Explorar Lugares</a>
                <a href="<?php echo url('tours'); ?>" class="btn-secondary">Ver Tours</a>
            </div>
        </div>
    </div>
</section>

<!-- Lugares Destacados -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Lugares Destacados</h2>
        
        <?php if (!empty($lugaresDestacados)): ?>
        <div class="card-grid">
            <?php foreach ($lugaresDestacados as $lugar): ?>
            <div class="card">
                <div class="card-image" style="background-color: #4a9d5f;">
                    <span class="card-category"><?php echo ucfirst($lugar['categoria']); ?></span>
                </div>
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($lugar['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($lugar['descripcion'], 0, 100)) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span class="rating">
                            <?php 
                            $calificacion = isset($lugar['promedio_calificacion']) ? round($lugar['promedio_calificacion']) : 0;
                            for($i = 1; $i <= 5; $i++) {
                                echo $i <= $calificacion ? '★' : '☆';
                            }
                            ?>
                            <small>(<?php echo $lugar['total_comentarios'] ?? 0; ?>)</small>
                        </span>
                        <span class="price"><?php echo formatearPrecio($lugar['precio_entrada']); ?></span>
                    </div>
                    
                    <a href="<?php echo url('lugares/detalle?id=' . $lugar['id_lugar']); ?>" class="btn-sm">Ver Detalles</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center">No hay lugares disponibles en este momento.</p>
        <?php endif; ?>
        
        <div class="text-center mt-2">
            <a href="<?php echo url('lugares'); ?>" class="btn-secondary">Ver Todos los Lugares</a>
        </div>
    </div>
</section>

<!-- Tours Populares -->
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title">Tours Más Reservados</h2>
        
        <?php if (!empty($toursMasReservados)): ?>
        <div class="card-grid">
            <?php foreach ($toursMasReservados as $tour): ?>
            <div class="card">
                <div class="card-image" style="background-color: #2d5016;">
                    <span class="card-badge">Popular</span>
                </div>
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($tour['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($tour['descripcion'], 0, 100)) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span>👥 Cupo: <?php echo $tour['cupo_maximo']; ?> personas</span>
                        <span class="price"><?php echo formatearPrecio($tour['precio']); ?></span>
                    </div>
                    
                    <?php if (isLoggedIn() && !isAdmin()): ?>
                    <a href="<?php echo url('reservas/crear?tour=' . $tour['id_tour']); ?>" class="btn-primary">Reservar Ahora</a>
                    <?php else: ?>
                    <a href="<?php echo url('tours/detalle?id=' . $tour['id_tour']); ?>" class="btn-sm">Ver Detalles</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center">No hay tours disponibles en este momento.</p>
        <?php endif; ?>
        
        <div class="text-center mt-2">
            <a href="<?php echo url('tours'); ?>" class="btn-secondary">Ver Todos los Tours</a>
        </div>
    </div>
</section>

<!-- Categorías de Lugares -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Explora por Categoría</h2>
        
        <div class="category-grid">
            <?php foreach ($categorias as $key => $nombre): ?>
            <a href="<?php echo url('lugares?categoria=' . $key); ?>" class="category-card">
                <div class="category-icon">
                    <?php
                    $iconos = [
                        'mirador' => '🏔️',
                        'cascada' => '💧',
                        'aventura' => '🎒',
                        'cultural' => '🏛️'
                    ];
                    echo $iconos[$key] ?? '📍';
                    ?>
                </div>
                <h3><?php echo $nombre; ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Últimos Comentarios -->
<?php if (!empty($ultimosComentarios)): ?>
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title">Lo Que Dicen Nuestros Visitantes</h2>
        
        <div class="testimonials">
            <?php foreach (array_slice($ultimosComentarios, 0, 3) as $comentario): ?>
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?php echo $i <= $comentario['calificacion'] ? '★' : '☆'; ?>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-text">"<?php echo htmlspecialchars($comentario['comentario']); ?>"</p>
                <div class="testimonial-author">
                    <strong><?php echo htmlspecialchars($comentario['usuario_nombre']); ?></strong>
                    <small>en <?php echo htmlspecialchars($comentario['lugar_nombre']); ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<?php if (!isLoggedIn()): ?>
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>¿Listo para tu aventura en Coroico?</h2>
            <p>Regístrate ahora y comienza a explorar los mejores destinos turísticos</p>
            <a href="<?php echo url('usuario/registro'); ?>" class="btn-primary btn-lg">Registrarse Gratis</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>