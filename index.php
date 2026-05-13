<?php 
session_start(); 
require_once 'includes/db.php'; 
require_once 'includes/header.php';
$sql = "SELECT * FROM Servicios WHERE id_servicio IN (
            SELECT MIN(id_servicio) 
            FROM Servicios 
            GROUP BY nombre
        ) 
        ORDER BY RAND() 
        LIMIT 4";

$stmt = $pdo->query($sql);
$servicios = $stmt->fetchAll();
?>
<section class="hero-section" id="inicio">
    <div class="hero-content">
        <h1>Bienvenido a LC Quiromasajes</h1>
        <p>Tu bienestar en manos profesionales. Especialistas en terapias manuales y recuperación corporal en Roquetas de Mar.</p>
        <div class="hero-actions">
            <a href="#servicios" class="btn btn-primary" id="btnReservarHero">Ver Tratamientos</a>
            <?php
            // Decidimos el destino según si hay sesión iniciada
            $destino_reserva = isset($_SESSION['user_id']) ? 'reservar.php' : 'auth/login.php';
            ?>
            <a href="<?= $destino_reserva ?>" class="btn btn-primary">Reservar Cita</a>
        </div>
    </div>
</section>

<section class="services-section" id="nosotros" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="section-header">
        <h2 style="font-family: var(--font-title); color: var(--color-primary);">Conoce Nuestra Historia</h2>
        <p>Descubre los inicios de LC Quiromasajes y nuestra pasión por tu bienestar.</p>
    </div>
    
    <div class="contenedor-video">
        <iframe class="iframe-video"
            src="https://www.youtube-nocookie.com/embed/6NZK6PnztMI?rel=0&modestbranding=1&controls=1&iv_load_policy=3" 
            title="Historia de LcQuiromasajes" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>        
    </div>
</section>

<section class="services-section" id="servicios">
    <div class="section-header">
        <h2>Tratamientos Destacados</h2>
        <p>Descubre algunas de nuestras terapias</p>
    </div>
    
    <div class="services-grid">
        <?php foreach ($servicios as $servicio): ?>
            <div class="service-card">
                <div>
                    <div style="margin-bottom: 15px;">
                        <span class="etiqueta-estado estado-completado" style="background: #E8F5E9; color: #2E7D32;">✨ Recomendado</span>
                    </div>
                    <h3><?php echo htmlspecialchars($servicio['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                    <div style="margin-bottom: 20px;">
                        <span style="display: block; font-weight: 600; color: var(--color-primary);">
                            <?php echo $servicio['duracion_minutos']; ?> min
                        </span>
                        <span style="font-size: 1.5rem; font-weight: 700;">
                            <?php echo number_format($servicio['precio_actual'], 2, ',', '.'); ?>€
                        </span>
                    </div>
                </div>
                <a href="reservar.php?id=<?php echo $servicio['id_servicio']; ?>" class="btn btn-outline-primary btn-sm">
                    Reservar ahora
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 50px;">
        <a href="tienda/index.php#tratamientos" class="btn btn-primary">
            Ver todos los tratamientos
        </a>
    </div>
</section>
</main>
<?php include 'includes/footer.php'; ?>