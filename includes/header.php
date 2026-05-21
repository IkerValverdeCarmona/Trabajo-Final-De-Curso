<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

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
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">

    <script src="/assets/js/script.js" defer></script>
</head>
<body>

<header class="navbar-custom">
    <div class="nav-container-custom">

        <!-- Logo -->
        <a href="<?php echo BASE_URL; ?>index.php" class="brand-logo">
            LC Quiromasajes
        </a>

        <!-- Nav central (escritorio) -->
        <nav class="nav-center" id="navMenu">
            <ul class="nav-links-list">
                <li><a href="<?php echo BASE_URL; ?>index.php">Inicio</a></li>
                <li><a href="<?php echo BASE_URL; ?>tienda/index.php#tratamientos">Tratamientos</a></li>
                <li><a href="<?php echo BASE_URL; ?>tienda/index.php#productos">Productos</a></li>
                <li><a href="<?php echo BASE_URL; ?>resenas.php">Reseñas</a></li>
            </ul>
        </nav>

        <!-- Zona derecha -->
        <div class="nav-right">

            <!-- Carrito -->
            <a href="<?php echo BASE_URL; ?>tienda/carrito.php" class="icono-carrito">
                🛒
                <?php if ($cantidad_carrito_header > 0): ?>
                    <span class="badge-carrito"><?php echo $cantidad_carrito_header; ?></span>
                <?php endif; ?>
            </a>

            <?php if (!isset($_SESSION['user_id'])): ?>

                <!-- No logueado: botones auth -->
                <div class="auth-buttons-header">
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="login-link-header">
                        Iniciar Sesión
                    </a>
                    <a href="<?php echo BASE_URL; ?>auth/registro.php" class="btn btn-primary btn-sm btn-registro-header">
                        Registrarse
                    </a>
                </div>

            <?php else: ?>

                <!-- Logueado: avatar + dropdown -->
                <div class="contenedor-usuario-header">
                    <div id="userMenuBtn" class="btn-usuario">
                        <div class="avatar-circulo">
                            <?php echo strtoupper(substr($_SESSION['nombre_real'] ?? 'U', 0, 1)); ?>
                        </div>
                        <span class="nombre-usuario">
                            <?php echo htmlspecialchars($_SESSION['nombre_real'] ?? 'Usuario'); ?>
                        </span>
                        <span class="flecha-dropdown">▼</span>
                    </div>

                    <div class="menu-desplegable" id="userDropdown">
                        <div class="menu-cabecera">
                            <p style="margin:0;font-size:0.75rem;color:#888;text-transform:uppercase;letter-spacing:1px;font-weight:600;">
                                Mi Cuenta
                            </p>
                        </div>
                        <div style="padding:5px 0;">
                            <a href="<?php echo BASE_URL; ?>mis_citas.php">📅 Mis Citas</a>
                            <a href="<?php echo BASE_URL; ?>mis_pedidos.php">🛍️ Mis Pedidos</a>
                            <a href="<?php echo BASE_URL; ?>perfil.php">👤 Mi Perfil</a>
                        </div>
                        <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'trabajador')): ?>
                            <div class="separador-menu"></div>
                            <a href="<?php echo BASE_URL; ?>admin/index.php" style="color:var(--color-primary);font-weight:600;">
                                ⚙️ Panel de Gestión
                            </a>
                        <?php endif; ?>
                        <div class="separador-menu"></div>
                        <a href="<?php echo BASE_URL; ?>auth/logout.php" style="color:#c5221f;">
                            🚪 Cerrar Sesión
                        </a>
                    </div>
                </div>

            <?php endif; ?>
            <button id="mobileMenuToggle" class="btn-menu-movil" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div><!-- /nav-right -->
    </div><!-- /nav-container-custom -->
</header>

<script>
(function () {
    var btnUser   = document.getElementById('userMenuBtn');
    var menuUser  = document.getElementById('userDropdown');

    if (btnUser && menuUser) {
        btnUser.addEventListener('click', function (e) {
            e.stopPropagation();
            menuUser.classList.toggle('show'); 
        });
    }

    // Menú móvil
    var btnMobile = document.getElementById('mobileMenuToggle');
    var navMenu   = document.getElementById('navMenu');

    if (btnMobile && navMenu) {
        btnMobile.addEventListener('click', function (e) {
            e.stopPropagation();
            navMenu.classList.toggle('active');
            btnMobile.classList.toggle('active');
        });
    }

    // Cerrar al hacer click fuera
    document.addEventListener('click', function (e) {
        if (menuUser && btnUser &&
            !menuUser.contains(e.target) && !btnUser.contains(e.target)) {
            menuUser.classList.remove('show');
        }
        if (navMenu && btnMobile &&
            !navMenu.contains(e.target) && !btnMobile.contains(e.target)) {
            navMenu.classList.remove('active');
            btnMobile.classList.remove('active');
        }
    });
})();
</script>

<main>