<?php
// 1. Asegurarnos de que BASE_URL existe para que no se rompan las rutas
// (Si tu proyecto está en una carpeta de xampp, cámbialo, ej: define('BASE_URL', '/lc_quiromasajes/'); )
if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); 
}

//Calcular cuántos productos hay en el carrito para mostrar el globito en el menú
$cantidad_carrito_header = 0;
if (isset($_SESSION['carrito'])) {
    $cantidad_carrito_header = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LC Quiromasajes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #FFF7EE;
            color: #333;
        }
        .navbar {
            background-color: #FFFFFF;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #EB6250;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #EB6250;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        /* Estilos del carrito en el header */
        .cart-icon-container {
            position: relative;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-size: 1.2rem;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #EB6250;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50px;
        }
        .btn-login {
            background-color: #EB6250;
            color: white !important;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= BASE_URL ?>index.php" class="logo">LC Quiromasajes</a>

    <div class="nav-links">
        <a href="<?= BASE_URL ?>index.php">Inicio</a>
        <a href="<?= BASE_URL ?>index.php#servicios">Tratamientos</a>
        <a href="<?= BASE_URL ?>tienda/index.php">Productos</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>mis_citas.php">Mis Citas</a>
        <?php endif; ?>
    </div>

    <div class="user-menu">
        
        <a href="<?= BASE_URL ?>tienda/carrito.php" class="cart-icon-container">
            🛍️
            <?php if ($cantidad_carrito_header > 0): ?>
                <span class="cart-badge"><?= $cantidad_carrito_header ?></span>
            <?php endif; ?>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="font-size: 0.9rem;">Hola, <strong><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></strong></span>
            
            <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
                <a href="<?= BASE_URL ?>admin/index.php" style="color: #886752; font-size: 0.9rem; text-decoration: none;">⚙️ Panel</a>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>auth/logout.php" style="color: #c5221f; font-size: 0.9rem; text-decoration: none; font-weight: 500;">Salir</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>auth/login.php" class="btn-login">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</nav>