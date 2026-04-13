<?php
// includes/header.php
// Nota: session_start() y require_once 'db.php' deben ir EN EL ARCHIVO PRINCIPAL antes de llamar a este header.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LC Quiromasajes | Centro de Terapias y Bienestar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    
    <style>
        body {
            background: linear-gradient(to bottom, #FFF7EE, #FDF2D8);
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-btn {
            background-color: #EB6250;
            color: #FFFFFF;
            border-radius: 50px;
            padding: 10px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .main-btn:hover {
            background-color: #D75443;
        }
        .input-radius {
            border-radius: 12px;
            border: 1px solid #ccc;
            padding: 8px 12px;
        }
        main {
            flex: 1; /* Esto empuja el footer hacia abajo */
        }
    </style>
</head>
<body>
    <header class="navbar" id="navbar" style="background-color: #FFFFFF; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);">
        <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 5%;">
            
            <a href="index.php" class="brand-logo" style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #EB6250; text-decoration: none;">
                LC Quiromasajes
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>

            <nav class="nav-links" id="navLinks" style="display: flex; align-items: center; gap: 20px;">
                <ul style="display: flex; list-style: none; gap: 15px; margin: 0; padding: 0;">
                    <li><a href="index.php#inicio" style="text-decoration: none; color: #333; font-weight: 500;">Inicio</a></li>
                    <li><a href="index.php#servicios" style="text-decoration: none; color: #333; font-weight: 500;">Tratamientos</a></li>
                    <li><a href="index.php#instalaciones" style="text-decoration: none; color: #333; font-weight: 500;">El Centro</a></li>
                </ul>
                
                <div class="nav-controls" style="display: flex; gap: 10px;">
                    <input type="text" placeholder="Buscar terapia..." class="input-radius" id="searchInput">
                    <select class="input-radius" id="languageSelect">
                        <option value="es">ES</option>
                        <option value="en">EN</option>
                    </select>
                </div>

                <div class="user-actions">
                    <?php if (!isset($_SESSION['id_perfil'])): ?>
                        <div id="guestState" style="display: flex; gap: 10px; align-items: center;">
                            <a href="login/index.html" style="color: #EB6250; text-decoration: none; font-weight: 500;">Iniciar Sesión</a>
                            <a href="login/index.html?action=register" class="main-btn">Registrarse</a>
                        </div>
                    
                    <?php else: ?>
                        <div id="loggedState" class="user-profile" style="position: relative;">
                            <div class="user-trigger" id="userMenuBtn" style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <div class="avatar" style="background-color: #EB6250; color: white; width: 35px; height: 35px; border-radius: 50px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?>
                                </div>
                                <span class="user-name" style="font-weight: 500;">Mi Cuenta</span>
                            </div>

                            <div class="dropdown-menu" id="userDropdown" style="display: none; position: absolute; right: 0; top: 100%; background: #FFF; padding: 15px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); min-width: 150px; z-index: 100;">
                                <div class="dropdown-header">
                                    <p class="text-muted" style="margin: 0; font-size: 0.85rem; word-break: break-all;"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                                </div>
                                <hr style="border: none; border-top: 1px solid #eee; margin: 10px 0;">
                                <a href="mis_citas.php" style="display: block; text-decoration: none; color: #333; padding: 5px 0;">Mis Citas</a>
                                <a href="perfil.php" style="display: block; text-decoration: none; color: #333; padding: 5px 0;">Mi Perfil</a> 
                                <hr style="border: none; border-top: 1px solid #eee; margin: 10px 0;">
                                <a href="login/logout.php" style="display: block; text-decoration: none; color: #dc3545; padding: 5px 0;">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
        <script>
    document.addEventListener("DOMContentLoaded", function() {
        const userMenuBtn = document.getElementById("userMenuBtn");
        const userDropdown = document.getElementById("userDropdown");

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener("click", function(event) {
                event.stopPropagation(); 
                
  
                if (userDropdown.style.display === "none" || userDropdown.style.display === "") {
                    userDropdown.style.display = "block";
                } else {
                    userDropdown.style.display = "none";
                }
            });

            document.addEventListener("click", function(event) {
                if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.style.display = "none";
                }
            });
        }
    });
</script>
    </header>
    
    <main>