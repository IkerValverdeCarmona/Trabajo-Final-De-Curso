<?php 
session_start(); 
require_once '../includes/db.php'; 
require_once '../includes/header.php';

// Consulta para Servicios Destacados
$sql = "SELECT * FROM Servicios 
        WHERE id_servicio IN (
            SELECT MIN(id_servicio) 
            FROM Servicios 
            GROUP BY nombre
        ) 
        ORDER BY RAND() 
        LIMIT 4";

$stmt = $pdo->query($sql);
$servicios = $stmt->fetchAll();


// ==========================
// CONSULTA RESEÑAS
// ==========================
try {

    $sql_resenas = "SELECT * 
                     FROM Opiniones 
                     WHERE visible = 1 
                     ORDER BY id_opinion DESC 
                     LIMIT 3";

    $stmt_resenas = $pdo->query($sql_resenas);
    $resenas = $stmt_resenas->fetchAll();

} catch (PDOException $e) {

    $resenas = [];

}
?>

<main>

<section class="hero-section" id="inicio">
    <div class="hero-content">

        <h1>Bienvenido a LC Quiromasajes</h1>

        <p>
            Tu bienestar en manos profesionales. 
            Especialistas en terapias manuales y recuperación corporal en Roquetas de Mar.
        </p>

        <div class="hero-actions">

            <a href="#servicios" class="btn btn-primary" id="btnReservarHero">
                Ver Tratamientos
            </a>

            <?php
            $destino_reserva = isset($_SESSION['user_id']) 
                ? PAGE_URL . "reservar.php" 
                : BASE_URL . "auth/login.php";
            ?>

            <a href="<?= $destino_reserva ?>" class="btn btn-primary">
                Reservar Cita
            </a>

        </div>

    </div>
</section>

<section class="services-section" id="nosotros" style="padding-top: 40px; padding-bottom: 60px;">

    <div class="section-header">

        <h2 style="font-family: var(--font-title); color: var(--color-primary);">
            Conoce Nuestra Historia
        </h2>

        <p>
            Descubre los inicios de LC Quiromasajes y nuestra pasión por tu bienestar.
        </p>

    </div>
    
    <div class="contenedor-video">

        <iframe 
            class="iframe-video"
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

        <p>
            Descubre algunas de nuestras terapias
        </p>

    </div>
    
    <div class="services-grid">

        <?php foreach ($servicios as $servicio): ?>

            <div class="service-card">

                <div>

                    <div style="margin-bottom: 15px;">
                        <span 
                            class="etiqueta-estado estado-completado" 
                            style="background: #E8F5E9; color: #2E7D32;"
                        >
                            ✨ Recomendado
                        </span>
                    </div>

                    <h3>
                        <?php echo htmlspecialchars($servicio['nombre']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($servicio['descripcion']); ?>
                    </p>

                    <div style="margin-bottom: 20px;">

                        <span style="display: block; font-weight: 600; color: var(--color-primary);">
                            <?php echo $servicio['duracion_minutos']; ?> min
                        </span>

                        <span style="font-size: 1.5rem; font-weight: 700;">
                            <?php echo number_format($servicio['precio_actual'], 2, ',', '.'); ?>€
                        </span>

                    </div>

                </div>

                <a 
                    href="<?php echo PAGE_URL; ?>reservar.php?id=<?php echo $servicio['id_servicio']; ?>" 
                    class="btn btn-outline-primary btn-sm"
                >
                    Reservar ahora
                </a>

            </div>

        <?php endforeach; ?>

    </div>

    <div style="text-align: center; margin-top: 50px;">

        <a href="<?php echo BASE_URL; ?>tienda/index.php#tratamientos" class="btn btn-primary">
            Ver todos los tratamientos
        </a>

    </div>

</section>

<section id="resenas" style="padding: 80px 20px; background-color: #FFF7EE;">

    <h2 style="
        text-align: center;
        font-family: 'Playfair Display', serif;
        color: var(--color-primary);
        margin-bottom: 50px;
    ">
        Lo que dicen nuestros clientes
    </h2>
    
    <div style="
        display: flex;
        gap: 30px;
        justify-content: center;
        flex-wrap: wrap;
    ">

        <?php if (empty($resenas)): ?>

            <p style="text-align: center;">
                Aún no hay reseñas públicas.
            </p>

        <?php else: ?>

            <?php foreach ($resenas as $r): ?>

                <?php
                // Obtener email manualmente SIN JOIN
                $email = 'Cliente';

                if (isset($r['id_perfil'])) {

                    $id_perfil = (int)$r['id_perfil'];

                     $stmtPerfil = $pdo->query("
                        SELECT email 
                        FROM Perfil 
                        WHERE id_perfil = $id_perfil 
                        LIMIT 1
                    ");

                    $perfil = $stmtPerfil->fetch();

                    if ($perfil && isset($perfil['email'])) {
                        $email = $perfil['email'];
                    }
                }

                // Valoración segura
                $valoracion = isset($r['puntuacion']) 
                    ? (int)$r['puntuacion'] 
                    : 5;

                // Comentario seguro
                $comentario = isset($r['comentario']) 
                    ? $r['comentario'] 
                    : 'Excelente servicio.';
                ?>
        

                <div style="
                    background: #FFFFFF;
                    padding: 35px;
                    border-radius: 20px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                    width: 350px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                ">

                    <div>

                        <div style="
                            color: #FFB400;
                            margin-bottom: 15px;
                            font-size: 1.2rem;
                        ">
                            <?php echo str_repeat('★', $valoracion); ?>
                        </div>

                        <p style="
                            font-family: 'Poppins', sans-serif;
                            font-size: 16px;
                            line-height: 1.8;
                            color: #444;
                            font-style: italic;
                        ">
                            "<?php echo htmlspecialchars($comentario); ?>"
                        </p>

                    </div>
                    
                    <div style="
                        margin-top: 25px;
                        border-top: 1px solid #eee;
                        padding-top: 15px;
                    ">

                        <h4 style="
                            font-family: 'Poppins', sans-serif;
                            font-weight: 600;
                            color: #333;
                            margin: 0;
                            font-size: 1.1rem;
                        ">
                            <?php echo htmlspecialchars($email); ?>
                        </h4>

                        <span style="
                            font-size: 0.85rem;
                            color: #888;
                        ">
                            Cliente verificado
                        </span>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</section>

</main>

<?php include '../includes/footer.php'; ?>