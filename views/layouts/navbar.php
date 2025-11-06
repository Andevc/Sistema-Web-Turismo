<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="<?php echo url(''); ?>">
                <h1>🏔️ <?php echo SITE_NAME; ?></h1>
            </a>
        </div>
        
        <ul class="navbar-menu">
            <li><a href="<?php echo url(''); ?>">Inicio</a></li>
            <li><a href="<?php echo url('lugares'); ?>">Lugares Turísticos</a></li>
            <li><a href="<?php echo url('tours'); ?>">Tours</a></li>
            
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url('usuario/mis-reservas'); ?>">Mis Reservas</a></li>
                <?php endif; ?>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        👤 <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url('usuario/perfil'); ?>">Mi Perfil</a></li>
                        <?php if (!isAdmin()): ?>
                        <li><a href="<?php echo url('usuario/mis-reservas'); ?>">Mis Reservas</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo url('usuario/logout'); ?>">Cerrar Sesión</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?php echo url('usuario/login'); ?>">Iniciar Sesión</a></li>
                <li><a href="<?php echo url('usuario/registro'); ?>" class="btn-primary">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>