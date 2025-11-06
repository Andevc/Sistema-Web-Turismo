</main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p><?php echo SITE_DESCRIPTION; ?></p>
                </div>
                
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="<?php echo url('lugares'); ?>">Lugares Turísticos</a></li>
                        <li><a href="<?php echo url('tours'); ?>">Tours Disponibles</a></li>
                        <?php if (isLoggedIn()): ?>
                        <li><a href="<?php echo url('usuario/mis-reservas'); ?>">Mis Reservas</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p>📍 Coroico, La Paz - Bolivia</p>
                    <p>📧 info@turismocoroico.com</p>
                    <p>📱 +591 2 123-4567</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?php echo url('public/js/scripts.js'); ?>"></script>
    <script src="<?php echo url('public/js/storage.js'); ?>"></script>
</body>
</html>