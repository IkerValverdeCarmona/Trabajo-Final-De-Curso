<?php
session_start();
if (!defined("BASE_URL")) define("BASE_URL", "../");
if (!defined("PAGE_URL")) define("PAGE_URL", "../public/");
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$mensaje_exito = "";
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje_exito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}

try {
    $sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.precio_final, 
                   s.nombre AS servicio, s.duracion_minutos, t.nombre AS especialista
            FROM Citas c
            JOIN Servicios s ON c.id_servicio = s.id_servicio
            LEFT JOIN Trabajadores t ON c.id_trabajador = t.id_trabajador
            WHERE c.id_perfil = ? ORDER BY c.fecha_hora DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar las citas: " . $e->getMessage());
}

include '../includes/header.php';
?>

<div class="hero-seccion">
    <h1>Tus Citas</h1>
    <p>Revisa tus momentos de relax y gestiona tus citas en LC Quiromasajes.</p>
</div>

<main class="contenedor-citas">    
    
    <?php if ($mensaje_exito): ?>
        <div class="alerta-exito" style="text-align: center; font-weight: 600;">✨ <?php echo $mensaje_exito; ?></div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
        <div class="tarjeta-vacia">
            <div class="tarjeta-vacia-icono">🧘‍♀️</div>
            <h3>Aún no has empezado tu viaje</h3>
            <p style="color: #777; margin-bottom: 30px;">Tu cuerpo merece un respiro. Descubre nuestros tratamientos personalizados.</p>
            <a href="<?php echo PAGE_URL; ?>index.php#servicios" class="btn btn-primary">Explorar Tratamientos</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 40px;">
            <?php foreach ($citas as $cita): 
                $timestamp = strtotime($cita['fecha_hora']);
                $fecha_dia = date('d', $timestamp);
                $fecha_mes = date('M', $timestamp);
                $hora_bonita = date('H:i', $timestamp);

                $clase_estado = 'estado-pendiente';
                if (strtolower($cita['estado']) == 'completada') $clase_estado = 'estado-completado';
                if (strtolower($cita['estado']) == 'cancelada') $clase_estado = 'estado-cancelado';

                $titulo_cal = urlencode("Reserva: " . $cita['servicio'] . " | LC Quiromasajes");
                $inicio_cal = date('Ymd\THis', $timestamp);
                $fin_cal = date('Ymd\THis', $timestamp + ($cita['duracion_minutos'] * 60));
                $detalles_cal = urlencode("Cita con " . ($cita['especialista'] ?? 'Staff') . ". ¡Relájate y disfruta!");
                $location_cal = urlencode("Avenida María Guerrero, 1, 04740 Roquetas de Mar, Almería, Spain");
                $google_cal_link = "https://www.google.com/calendar/render?action=TEMPLATE&text=$titulo_cal&dates=$inicio_cal/$fin_cal&details=$detalles_cal&location=$location_cal&sf=true&output=xml";
            ?>
                <div>
                    <div class="timeline-date"><?php echo $fecha_dia . " " . strtoupper($fecha_mes); ?></div>
                    
                    <div class="cita-card">
                        <div style="flex: 1; min-width: 300px;">
                            <div style="margin-bottom: 15px;">
                                <span class="etiqueta-estado <?php echo $clase_estado; ?>" style="text-transform: uppercase;">
                                    <?php echo htmlspecialchars($cita['estado']); ?>
                                </span>
                            </div>
                            
                            <h3 style="margin: 0 0 10px 0; font-size: 1.6rem; font-family: var(--font-title);"><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                            
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 15px; color: var(--color-text-muted); font-size: 0.95rem;">
                                <div><span>👤</span> <strong><?php echo htmlspecialchars($cita['especialista'] ?? 'LcQuiromasajes'); ?></strong></div>
                                <div><span>🕛</span> <?php echo $cita['duracion_minutos']; ?> min</div>
                                <div><span>📍</span> Calle María Guerrero Nº1, Roquetas de Mar</div>
                            </div>
                        </div>

                        <div style="text-align: right; border-left: 1px solid #f0f0f0; padding-left: 30px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <div style="color: var(--color-primary); font-size: 2.2rem; font-weight: 800; line-height: 1;"><?php echo $hora_bonita; ?></div>
                                <div style="font-size: 1.2rem; font-weight: 600; margin-top: 5px;">
                                    <?php echo number_format($cita['precio_final'], 2, ',', '.'); ?>€
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-end;">
                                <?php if (strtolower($cita['estado']) == 'pendiente'): ?>
                                    <a href="<?php echo $google_cal_link; ?>" target="_blank" class="btn-cal">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Google_Calendar_icon_%282020%29.svg" width="18" alt="Google">
                                        Google Calendar
                                    </a>

                                    <form action="cancelar_cita.php" method="POST" onsubmit="return confirm('¿Deseas cancelar tu momento de bienestar?');" style="margin: 0;">
                                        <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                        <button type="submit" class="enlace-quitar" style="background: none; border: none; cursor: pointer;">Cancelar Cita</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>   
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>