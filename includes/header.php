<?php
// 1. Asegurarnos de que BASE_URL existe
if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); 
}

// 2. Calcular cuántos productos hay en el carrito
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
        /* Diseño estructural del Header */
        .navbar-custom {
            background-color: #FFFFFF;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            font-family: 'Poppins', sans-serif;
        }
        
        .nav-container-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 5%;
        }

        /* 3 Bloques equilibrados */
        .nav-left { flex: 1; display: flex; justify-content: flex-start; }
        .nav-center { flex: 2; display: flex; justify-content: center; }
        .nav-right { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 25px; }

        .nav-links-list {
            display: flex;
            list-style: none;
            gap: 35px;
            margin: 0;
            padding: 0;
        }

        .nav-links-list a {
            text-decoration: none;
            color: #444;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        
        .nav-links-list a:hover {
            color: #EB6250;
        }

        /* Icono Carrito */
        .cart-icon-container {
            position: relative;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-size: 1.4rem;
            transition: transform 0.2s;
        }
        .cart-icon-container:hover { transform: scale(1.1); }
        .cart-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background-color: #EB6250;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50px;
            border: 2px solid white;
        }

        /* Menú Desplegable */
        .dropdown-menu-custom {
            position: absolute;
            right: 0;
            top: calc(100% + 15px);
            background: white;
            border-radius: 15px;
            min-width: 220px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: none;
            z-index: 9999;
            border: 1px solid #f5f5f5;
            padding: 10px 0;
            animation: slideIn 0.2s ease-out;
        }
        .dropdown-menu-custom.show { display: block; }
        .dropdown-menu-custom a {
            display: block;
            padding: 10px 20px;
            color: #444;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .dropdown-menu-custom a:hover { background: #FFF7EE; color: #EB6250; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <header class="navbar-custom">
        <div class="nav-container-custom">
            
            <div class="nav-left">
                <a href="<?php echo BASE_URL; ?>index.php" style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 1.8rem; font-weight: 700; text-decoration: none; letter-spacing: -0.5px;">
                    LC Quiromasajes
                </a>
            </div>

            <nav class="nav-center">
                <ul class="nav-links-list">
                    <li><a href="<?php echo BASE_URL; ?>index.php#inicio">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#servicios">Tratamientos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tienda/index.php">Productos</a></li>
                </ul>
            </nav>

            <div class="nav-right">
                
                <a href="<?php echo BASE_URL; ?>tienda/carrito.php" class="cart-icon-container">
                    🛒
                    <?php if ($cantidad_carrito_header > 0): ?>
                        <span class="cart-badge"><?php echo $cantidad_carrito_header; ?></span>
                    <?php endif; ?>
                </a>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <a href="<?php echo BASE_URL; ?>auth/login.php" style="color: #EB6250; text-decoration: none; font-weight: 500;">Iniciar Sesión</a>
                        <a href="<?php echo BASE_URL; ?>auth/registro.php" style="background-color: #EB6250; color: white; padding: 10px 24px; border-radius: 50px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 15px rgba(235,98,80,0.2);">Registrarse</a>
                    </div>
                <?php else: ?>
                    <div style="position: relative;">
                        <div id="userMenuBtn" style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 6px 12px; border-radius: 50px; border: 1px solid #eee; transition: background 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='transparent'">
                            <div style="background-color: #EB6250; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; pointer-events: none;">
                                <?php echo strtoupper(substr($_SESSION['nombre_real'] ?? 'U', 0, 1)); ?>
                            </div>
                            <span style="font-weight: 500; color: #333; font-size: 0.95rem; pointer-events: none;">
                                <?php echo htmlspecialchars($_SESSION['nombre_real'] ?? 'Usuario'); ?>
                            </span>
                            <span style="font-size: 0.6rem; color: #888; pointer-events: none;">▼</span>
                        </div>

                        <div class="dropdown-menu-custom" id="userDropdown">
                            <div style="padding: 10px 20px; background: #fafafa; border-radius: 15px 15px 0 0; margin-top: -10px; border-bottom: 1px solid #f0f0f0;">
                                <p style="margin: 0; font-size: 0.75rem; color: #888; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Mi Cuenta</p>
                            </div>
                            
                            <div style="padding: 5px 0;">
                                <a href="<?php echo BASE_URL; ?>mis_citas.php">📅 Mis Citas</a>
                                <a href="<?php echo BASE_URL; ?>mis_pedidos.php">🛍️ Mis Pedidos</a>
                                <a href="<?php echo BASE_URL; ?>perfil.php">👤 Mi Perfil</a> 
                            </div>
                            
                            <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'trabajador')): ?>
                                <div style="border-top: 1px solid #f0f0f0; margin: 5px 0;"></div>
                                <a href="<?php echo BASE_URL; ?>admin/index.php" style="color: #EB6250; font-weight: 600;">⚙️ Panel de Gestión</a>
                            <?php endif; ?>
                            
                            <div style="border-top: 1px solid #f0f0f0; margin: 5px 0;"></div>
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
        </div>
    </header>
    <main>