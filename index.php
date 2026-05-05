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
<section class="services-section" id="nosotros" style="padding-top: 40px; padding-bottom: 40px;">
    <div class="section-header">
        <h2>Conoce Nuestra Historia</h2>
        <p>Descubre los inicios de LcQuiromasajes y nuestra pasión por tu bienestar.</p>
    </div>
    
    <!-- Contenedor adaptado para formato vertical (Short) -->
    <div style="max-width: 400px; margin: 0 auto; border-radius: var(--radius-card); overflow: hidden; box-shadow: var(--shadow-card); background: #000; position: relative; padding-bottom: 177.77%; height: 0;">
        
        <iframe src="https://www.youtube-nocookie.com/embed/6NZK6PnztMI" 
                title="Historia de LcQuiromasajes" 
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
        </iframe>
        
    </div>
</section>
<section class="services-section" id="servicios">
    <div class="section-header">
        <h2>Nuestros Tratamientos</h2>
        <p>Selecciona el masaje que mejor se adapte a tus necesidades</p>
    </div>
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