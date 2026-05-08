<?php
session_start();
require_once 'includes/db.php';

// SEGURIDAD: Solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];

// Capturar mensaje de éxito de la reserva (si existe)
$mensaje_exito = "";
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje_exito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}

try {
    $sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.precio_final, 
                   s.nombre AS servicio, s.duracion_minutos,
                   t.nombre AS especialista
            FROM Citas c
            JOIN Servicios s ON c.id_servicio = s.id_servicio
            LEFT JOIN Trabajadores t ON c.id_trabajador = t.id_trabajador
            WHERE c.id_perfil = ?
            ORDER BY c.fecha_hora DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar las citas: " . $e->getMessage());
}

include 'includes/header.php';
?>

<style>
    .cita-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(235, 98, 80, 0.08);
    }
    .cita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(235, 98, 80, 0.12) !important;
        border-color: #EB6250;
    }
    .btn-cal {
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #3c4043;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid #dadce0;
        transition: 0.2s;
    }
    .btn-cal:hover {
        background: #f8f9fa;
        border-color: #EB6250;
    }
    .timeline-date {
        background: #EB6250;
        color: white;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: -15px;
        position: relative;
        z-index: 2;
        margin-left: 30px;
        box-shadow: 0 4px 10px rgba(235, 98, 80, 0.3);
    }
</style>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 70px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.8rem; margin-bottom: 10px;">Tu Diario de Bienestar</h1>
    <p style="color: #886752; max-width: 600px; margin: 0 auto; font-size: 1.1rem; opacity: 0.9;">Revisa tus momentos de relax y gestiona tus citas en LC Quiromasajes.</p>
</div>

<main style="width: 100%; max-width: 950px; margin: -40px auto 80px; padding: 0 20px; min-height: 50vh;">    
    
    <?php if ($mensaje_exito): ?>
        <div style="background: #e6f4ea; color: #1e7e34; padding: 20px; border-radius: 16px; margin-bottom: 40px; text-align: center; font-weight: 600; box-shadow: 0 10px 20px rgba(30,126,52,0.1); border: 1px solid #c3e6cb; animation: fadeIn 0.5s ease;">
            ✨ <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
        <div style="text-align: center; background: white; padding: 80px 20px; border-radius: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <div style="font-size: 5rem; margin-bottom: 20px;">🧘‍♀️</div>
            <h3 style="color: #333; margin-bottom: 15px; font-family: 'Playfair Display', serif; font-size: 1.8rem;">Aún no has empezado tu viaje</h3>
            <p style="color: #777; margin-bottom: 30px; font-size: 1.1rem;">Tu cuerpo merece un respiro. Descubre nuestros tratamientos personalizados.</p>
            <a href="index.php#servicios" style="background-color: #EB6250; color: white; padding: 18px 40px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; box-shadow: 0 10px 20px rgba(235,98,80,0.3);">Explorar Tratamientos</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 40px;">
            <?php foreach ($citas as $cita): 
                $timestamp = strtotime($cita['fecha_hora']);
                $fecha_dia = date('d', $timestamp);
                $fecha_mes = date('M', $timestamp);
                $hora_bonita = date('H:i', $timestamp);

                // Configuración de colores por estado
                $color_estado = '#666'; $bg_estado = '#f0f0f0';
                switch (strtolower($cita['estado'])) {
                    case 'pendiente': $color_estado = '#b7791f'; $bg_estado = '#fff4e5'; break;
                    case 'completada': $color_estado = '#1e7e34'; $bg_estado = '#e6f4ea'; break;
                    case 'cancelada': $color_estado = '#c5221f'; $bg_estado = '#fce8e6'; break;
                }

                // URL de Google Calendar
                $titulo_cal = urlencode("Reserva: " . $cita['servicio'] . " | LC Quiromasajes");
                $inicio_cal = date('Ymd\THis', $timestamp);
                $fin_cal = date('Ymd\THis', $timestamp + ($cita['duracion_minutos'] * 60));
                $detalles_cal = urlencode("Cita con " . ($cita['especialista'] ?? 'Staff') . ". ¡Relájate y disfruta!");
                $location_cal = urlencode("Avenida María Guerrero, 1, 04740 Roquetas de Mar, Almería, Spain");
                $google_cal_link = "https://www.google.com/calendar/render?action=TEMPLATE&text=$titulo_cal&dates=$inicio_cal/$fin_cal&details=$detalles_cal&location=$location_cal&sf=true&output=xml";
            ?>
                <div style="position: relative;">
                    <div class="timeline-date"><?php echo $fecha_dia . " " . strtoupper($fecha_mes); ?></div>
                    
                    <div class="cita-card" style="background: white; border-radius: 28px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 30px;">
                        
                        <div style="flex: 1; min-width: 300px;">
                            <div style="margin-bottom: 15px;">
                                <span style="background-color: <?php echo $bg_estado; ?>; color: <?php echo $color_estado; ?>; padding: 6px 18px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px;">
                                    <?php echo htmlspecialchars($cita['estado']); ?>
                                </span>
                            </div>
                            
                            <h3 style="color: #2D2D2D; margin: 0 0 10px 0; font-size: 1.6rem; font-family: 'Playfair Display', serif;"><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                            
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 15px; color: #666; font-size: 0.95rem;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span>👤</span> <strong><?php echo htmlspecialchars($cita['especialista'] ?? 'Staff LcQuiromasajes'); ?></strong>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span>⏱️</span> <?php echo $cita['duracion_minutos']; ?> min
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span>📍</span> Roquetas de Mar
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right; border-left: 1px solid #f0f0f0; padding-left: 30px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <div style="color: #EB6250; font-size: 2.2rem; font-weight: 800; line-height: 1;"><?php echo $hora_bonita; ?></div>
                                <div style="font-size: 1.2rem; font-weight: 600; color: #2D2D2D; margin-top: 5px;">
                                    <?php echo number_format($cita['precio_final'], 2, ',', '.'); ?>€
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-end;">
                                <?php if (strtolower($cita['estado']) == 'pendiente'): ?>
                                    <a href="<?php echo $google_cal_link; ?>" target="_blank" class="btn-cal">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Google_Calendar_icon_%282020%29.svg" width="18">
                                        Google Calendar
                                    </a>

                                    <form action="cancelar_cita.php" method="POST" onsubmit="return confirm('¿Deseas cancelar tu momento de bienestar?');" style="margin: 0;">
                                        <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                        <button type="submit" style="background: none; border: none; color: #999; font-size: 0.8rem; font-weight: 500; cursor: pointer; text-decoration: underline; padding: 5px;">
                                            Cancelar Cita
                                        </button>
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

<?php include 'includes/footer.php'; ?>