<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
    <title><?php echo isset($titulo) ? $titulo . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo url('public/css/styles.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo url('public/favicon.ico'); ?>">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    
    <!-- Mensajes Flash -->
    <?php
    $mensaje = getFlashMessage();
    if ($mensaje):
    ?>
    <div class="flash-message <?php echo $mensaje['tipo']; ?>">
        <div class="container">
            <p><?php echo htmlspecialchars($mensaje['texto']); ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <main class="main-content">