<?php 
session_start(); 
require_once 'includes/db.php'; 
include 'includes/header.php'; 

// Consultamos los servicios activos
$stmt = $pdo->query("SELECT * FROM Servicios WHERE activo = 1");
$servicios = $stmt->fetchAll();
?>

<section class="hero-section" id="inicio">
    <div class="hero-content">
        <h1>Bienvenido a LC Quiromasajes</h1>
        <p>Tu bienestar en manos profesionales. Especialistas en terapias manuales y recuperación corporal en Roquetas de Mar.</p>
        <div class="hero-actions">
            <a href="#servicios" class="btn btn-primary" id="btnReservarHero">Ver Tratamientos</a>
            <a href="login/index.html" class="btn btn-secondary">Reservar Cita</a>
        </div>
    </div>
</section>

<section class="services-section" id="servicios">
    <div class="section-header">
        <h2>Nuestros Tratamientos</h2>
        <p>Selecciona el masaje que mejor se adapte a tus necesidades</p>
    </div>
<section class="services-section" style="padding-top: 40px;">
    <div class="section-header">
        <h2>Conoce Nuestra Historia</h2>
        <p>Años de experiencia dedicados a tu bienestar y salud integral.</p>
    </div>
    
    <div style="max-width: 400px; margin: 0 auto; border-radius: var(--radius-card); overflow: hidden; box-shadow: var(--shadow-card); background: #FFFFFF;">
        
        <iframe src="media/video_presentacion.mp4" 
                width="100%" 
                height="580" 
                frameborder="0" 
                scrolling="no" 
                allowtransparency="true" 
                style="border:none; overflow:hidden;">
        </iframe>

    </div>
</section>
    <div class="services-grid">
        <?php foreach ($servicios as $servicio): ?>
            <div class="service-card">
                <div>
                    <h3><?php echo htmlspecialchars($servicio['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                    <div style="margin-bottom: 20px;">
                        <span style="display: block; font-weight: 600; color: #EB6250;">
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
</section>

</main>

<?php include 'includes/footer.php'; ?>