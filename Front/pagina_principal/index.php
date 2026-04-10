<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LC Quiromasajes | Centro de Terapias y Bienestar</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="brand-logo">LC Quiromasajes</a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>

            <nav class="nav-links" id="navLinks">
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#servicios">Tratamientos</a></li>
                    <li><a href="#instalaciones">El Centro</a></li>
                </ul>
                <div class="nav-controls">
                    <input type="text" placeholder="Buscar terapia..." class="input-control" id="searchInput">
                    <select class="input-control" id="languageSelect">
                        <option value="es">ES</option>
                        <option value="en">EN</option>
                    </select>
                </div>

                <div class="user-actions">
                    <?php if (!isset($_SESSION['id_perfil'])): ?>
                        <div id="guestState" style="display: flex; gap: 10px; align-items: center;">
                            <a href="../../Login/index.html" class="btn btn-outline-primary btn-sm">Iniciar Sesión</a>
                            <a href="../../Login/index.html?action=register" class="btn btn-primary btn-sm">Registrarse</a>
                        </div>
                    
                    <?php else: ?>
                        <div id="loggedState" class="user-profile">
                            <div class="user-trigger" id="userMenuBtn">
                                <div class="avatar"><?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?></div>
                                <span class="user-name">Mi Cuenta</span>
                            </div>

                            <div class="dropdown-menu" id="userDropdown">
                                <div class="dropdown-header">
                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                                </div>
                                <hr>
                                <a href="#mis-citas">Mis Citas</a>
                                <a href="perfil.php">Mi Perfil</a> 
                                <hr>
                                <a href="../../Login/logout.php" class="text-danger">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <script src="script.js"></script>
</body>
</html>