<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LC Quiromasajes | Centro de Terapias y Bienestar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LC Quiromasajes | Centro de Terapias y Bienestar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="navbar" id="navbar">
        <div class="nav-container">
            
            <a href="<?php echo BASE_URL; ?>index.php" class="brand-logo">
                LC Quiromasajes
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>

            <nav class="nav-links" id="navLinks">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>index.php#inicio">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#instalaciones">El Centro</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#servicios">Tratamientos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>tienda.php">Productos</a></li>
                </ul>
                
                <div class="nav-controls">
                    <input type="text" placeholder="Buscar terapia..." class="input-radius" id="searchInput" style="border-radius: 12px;">
                    <select class="input-radius" id="languageSelect" style="border-radius: 12px;">
                        <option value="es">ES</option>
                        <option value="en">EN</option>
                    </select>
                </div>

                <div class="user-actions">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div id="guestState" style="display: flex; gap: 10px; align-items: center;">
                            <a href="<?php echo BASE_URL; ?>auth/login.php" style="color: #EB6250; text-decoration: none; font-weight: 500;">Iniciar Sesión</a>
                            <a href="<?php echo BASE_URL; ?>auth/registro.php" class="main-btn" style="background-color: #EB6250; color: white; padding: 8px 20px; border-radius: 50px; text-decoration: none;">Registrarse</a>
                        </div>
                    
                    <?php else: ?>
                        <div id="loggedState" class="user-profile">
                            <div class="user-trigger" id="userMenuBtn" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <div class="avatar" style="background-color: #EB6250; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?php echo strtoupper(substr($_SESSION['nombre_real'], 0, 1)); ?>
                                </div>
                                <span class="user-name">Hola, <?php echo htmlspecialchars($_SESSION['nombre_real']); ?></span>
                            </div>

                            <div class="dropdown-menu" id="userDropdown">
                                <div class="dropdown-header">
                                    <p class="text-muted" style="margin: 0;">Mi Cuenta</p>
                                </div>
                                <hr>
                                <a href="<?php echo BASE_URL; ?>mis_citas.php">Mis Citas</a>
                                <a href="<?php echo BASE_URL; ?>perfil.php">Mi Perfil</a> 
                                
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'trabajador')): ?>
                                    <a href="<?php echo BASE_URL; ?>admin/admin.php">Panel Admin</a>
                                <?php endif; ?>
                                
                                <hr>
                                <a href="<?php echo BASE_URL; ?>auth/logout.php" class="text-danger" style="color: #D75443;">Cerrar Sesion</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    <main>