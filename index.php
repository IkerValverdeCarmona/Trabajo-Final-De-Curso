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
<section class="services-section" id="nosotros" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="section-header">
        <h2 style="font-family: 'Playfair Display', serif; color: #EB6250;">Conoce Nuestra Historia</h2>
        <p style="font-family: 'Poppins', sans-serif;">Descubre los inicios de LcQuiromasajes y nuestra pasión por tu bienestar.</p>
    </div>
    
    <div style="width: 90%; max-width: 350px; margin: 0 auto;">
        <div style="position: relative; padding-bottom: 177.78%; height: 0; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); background-color: #000;">
            <iframe 
                src="https://www.youtube-nocookie.com/embed/6NZK6PnztMI?rel=0&modestbranding=1&controls=1&iv_load_policy=3" 
                title="Historia de LcQuiromasajes" 
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
            
        </div>
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