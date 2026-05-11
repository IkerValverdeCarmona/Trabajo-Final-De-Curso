<?php
// 1. Asegurarnos de que BASE_URL existe para que no se rompan las rutas
// Si tu proyecto está en una subcarpeta (ej: htdocs/lc_quiromasajes), defínelo aquí.
// Si ya lo tienes en db.php, esta comprobación evita errores.
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
        /* Estilos específicos para el icono del carrito en el header */
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
    </style>
</head>
<body>
    <header class="navbar" id="navbar">
        <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 0 5%;">
            
            <a href="<?php echo BASE_URL; ?>index.php" class="brand-logo" style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 1.8rem; font-weight: 700; text-decoration: none;">
                LC Quiromasajes
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>

            <nav class="nav-links" id="navLinks" style="display: flex; align-items: center; gap: 30px;">
                <ul style="display: flex; list-style: none; gap: 20px; margin: 0; padding: 0;">
                    <li><a href="<?php echo BASE_URL; ?>index.php#inicio" style="text-decoration: none; color: #333; font-weight: 500;">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#instalaciones" style="text-decoration: none; color: #333; font-weight: 500;">El Centro</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#servicios" style="text-decoration: none; color: #333; font-weight: 500;">Tratamientos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tienda/index.php" style="text-decoration: none; color: #333; font-weight: 500;">Productos</a></li>
                </ul>
                
                <div class="nav-controls" style="display: flex; gap: 10px;">
                    <input type="text" placeholder="Buscar terapia..." class="input-radius" id="searchInput" style="border-radius: 12px; padding: 8px 15px; border: 1px solid #ddd;">
                    <select class="input-radius" id="languageSelect" style="border-radius: 12px; padding: 8px; border: 1px solid #ddd;">
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
                            
                            <div class="user-trigger" id="userMenuBtn" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <div class="avatar" style="background-color: #EB6250; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?php echo strtoupper(substr($_SESSION['nombre_real'] ?? 'U', 0, 1)); ?>
                                </div>
                                <span class="user-name" style="font-weight: 500; color: #333;">Hola, <?php echo htmlspecialchars($_SESSION['nombre_real'] ?? 'Usuario'); ?></span>
                            </div>

                            <div class="dropdown-menu" id="userDropdown" style="position: absolute; right: 0; top: 100%; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; padding: 15px; min-width: 200px; display: none; z-index: 1000;">
                                <div class="dropdown-header" style="margin-bottom: 10px;">
                                    <p class="text-muted" style="margin: 0; font-size: 0.85rem; color: #888;">Mi Cuenta</p>
                                </div>
                                <hr style="margin: 10px 0; border-color: #eee;">
                                
                                <a href="<?php echo BASE_URL; ?>mis_citas.php" style="display: block; padding: 8px 0; color: #333; text-decoration: none;">Mis Citas</a>
                                <a href="<?php echo BASE_URL; ?>perfil.php" style="display: block; padding: 8px 0; color: #333; text-decoration: none;">Mi Perfil</a> 
                                
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'trabajador')): ?>
                                    <a href="<?php echo BASE_URL; ?>admin/index.php" style="display: block; padding: 8px 0; color: #EB6250; font-weight: 600; text-decoration: none;">Panel de Gestión</a>
                                <?php endif; ?>
                                
                                <hr style="margin: 10px 0; border-color: #eee;">
                                <a href="<?php echo BASE_URL; ?>auth/logout.php" class="text-danger" style="display: block; padding: 8px 0; color: #c5221f; text-decoration: none;">Cerrar Sesión</a>
                            </div>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const userBtn = document.getElementById('userMenuBtn');
                                const dropdown = document.getElementById('userDropdown');
                                if(userBtn && dropdown) {
                                    userBtn.addEventListener('click', function(e) {
                                        e.stopPropagation();
                                        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                                    });
                                    document.addEventListener('click', function() {
                                        dropdown.style.display = 'none';
                                    });
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    <main>