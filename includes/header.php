<?php
// 1. Asegurarnos de que BASE_URL existe para que no se rompan las rutas
if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); 
}

// 2. Calcular cuántos productos hay en el carrito para el globo del menú
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
    <title>LC Quiromasajes | Centro de Terapias y Bienestar</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>">

    <style>
        /* Estilos específicos del header para Carrito y Menú de Usuario */
        .cart-icon-container {
            position: relative;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-size: 1.3rem;
            margin-right: 20px;
            transition: transform 0.2s;
        }
        .cart-icon-container:hover {
            transform: scale(1.1);
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
            border: 2px solid white;
        }

        .dropdown-menu-custom {
            position: absolute;
            right: 0;
            top: 120%;
            background: white;
            border-radius: 15px;
            min-width: 220px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            display: none; /* Oculto por defecto */
            z-index: 9999;
            border: 1px solid #f0f0f0;
            padding: 10px 0;
            animation: slideIn 0.2s ease-out;
        }

        .dropdown-menu-custom.show {
            display: block; /* Se muestra con JS */
        }

        .dropdown-menu-custom a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .dropdown-menu-custom a:hover {
            background: #FFF7EE;
            color: #EB6250;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <header class="navbar" id="navbar">
        <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 0 5%;">
            
            <a href="<?php echo BASE_URL; ?>index.php" class="brand-logo" style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 1.8rem; font-weight: 700; text-decoration: none;">
                LC Quiromasajes
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn" style="border: none; background: none; font-size: 1.5rem; cursor: pointer;">
                ☰
            </button>

            <nav class="nav-links" id="navLinks" style="display: flex; align-items: center; gap: 30px;">
                <ul style="display: flex; list-style: none; gap: 20px; margin: 0; padding: 0;">
                    <li><a href="<?php echo BASE_URL; ?>index.php#inicio" style="text-decoration: none; color: #333; font-weight: 500;">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#servicios" style="text-decoration: none; color: #333; font-weight: 500;">Tratamientos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tienda/index.php" style="text-decoration: none; color: #333; font-weight: 500;">Productos</a></li>
                </ul>
                
                <div class="nav-controls" style="display: flex; gap: 10px;">
                    <input type="text" placeholder="Buscar terapia..." class="input-radius" id="searchInput" style="border-radius: 12px; padding: 8px 15px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
                    <select class="input-radius" id="languageSelect" style="border-radius: 12px; padding: 8px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
                        <option value="es">ES</option>
                        <option value="en">EN</option>
                    </select>
                </div>

                <div class="user-actions" style="display: flex; align-items: center;">
                    
                    <a href="<?php echo BASE_URL; ?>tienda/carrito.php" class="cart-icon-container">
                        🛒
                        <?php if ($cantidad_carrito_header > 0): ?>
                            <span class="cart-badge"><?php echo $cantidad_carrito_header; ?></span>
                        <?php endif; ?>
                    </a>

                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div id="guestState" style="display: flex; gap: 15px; align-items: center;">
                            <a href="<?php echo BASE_URL; ?>auth/login.php" style="color: #EB6250; text-decoration: none; font-weight: 500;">Iniciar Sesión</a>
                            <a href="<?php echo BASE_URL; ?>auth/registro.php" class="main-btn" style="background-color: #EB6250; color: white; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 600;">Registrarse</a>
                        </div>
                    
                    <?php else: ?>
                        <div id="loggedState" class="user-profile" style="position: relative;">
                            
                            <div class="user-trigger" id="userMenuBtn" style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 5px 10px; border-radius: 12px; transition: background 0.3s;">
                                <div class="avatar" style="background-color: #EB6250; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; pointer-events: none;">
                                    <?php echo strtoupper(substr($_SESSION['nombre_real'] ?? 'U', 0, 1)); ?>
                                </div>
                                <span class="user-name" style="font-weight: 500; color: #333; pointer-events: none;">
                                    Hola, <?php echo htmlspecialchars($_SESSION['nombre_real'] ?? 'Usuario'); ?>
                                </span>
                                <span style="font-size: 0.7rem; pointer-events: none;">▼</span>
                            </div>

                            <div class="dropdown-menu-custom" id="userDropdown">
                                <div style="padding: 10px 15px;">
                                    <p style="margin: 0; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px;">Mi Cuenta</p>
                                </div>
                                <hr style="margin: 5px 0; border: 0; border-top: 1px solid #eee;">
                                
                                <a href="<?php echo BASE_URL; ?>mis_citas.php">📅 Mis Citas</a>
                                <a href="<?php echo BASE_URL; ?>perfil.php">👤 Mi Perfil</a> 
                                
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'trabajador')): ?>
                                    <a href="<?php echo BASE_URL; ?>admin/index.php" style="color: #EB6250; font-weight: 600;">⚙️ Panel de Gestión</a>
                                <?php endif; ?>
                                
                                <hr style="margin: 5px 0; border: 0; border-top: 1px solid #eee;">
                                <a href="<?php echo BASE_URL; ?>auth/logout.php" style="color: #c5221f;">🚪 Cerrar Sesión</a>
                            </div>
                        </div>

                        <script>
                            (function() {
                                const btn = document.getElementById('userMenuBtn');
                                const menu = document.getElementById('userDropdown');

                                if (btn && menu) {
                                    btn.addEventListener('click', function(e) {
                                        e.stopPropagation();
                                        menu.classList.toggle('show');
                                    });

                                    // Cerrar el menú si se hace clic fuera de él
                                    document.addEventListener('click', function(e) {
                                        if (!menu.contains(e.target) && !btn.contains(e.target)) {
                                            menu.classList.remove('show');
                                        }
                                    });
                                }
                            })();
                        </script>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    <main>