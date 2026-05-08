<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
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
        transition: all 0.3s ease;
        border: 1px solid rgba(235, 98, 80, 0.1);
    }
    .cita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(235, 98, 80, 0.1) !important;
        border-color: #EB6250;
    }
    .btn-cancelar {
        transition: all 0.2s;
        border: 1px solid #ff4d4d;
        color: #ff4d4d;
        padding: 5px 15px;
        border-radius: 50px;
        background: transparent;
    }
    .btn-cancelar:hover {
        background: #ff4d4d;
        color: white;
    }
    .timeline-date {
        background: #EB6250;
        color: white;
        padding: 5px 15px;
        border-radius: 50px 0 0 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: -15px;
        position: relative;
        z-index: 2;
        margin-left: 20px;
    }
</style>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 60px 20px; text-align: center;">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin-bottom: 10px;">Tu Diario de Bienestar</h1>
    <p style="color: #886752; max-width: 600px; margin: 0 auto; font-size: 1.1rem;">Gestiona tus momentos de relax y desconexión en LC Quiromasajes.</p>
</div>

<main style="width: 100%; max-width: 900px; margin: -30px auto 60px; padding: 0 20px; min-height: 50vh;">    
    
    <?php if ($mensaje_exito): ?>
        <div style="background: #e6f4ea; color: #1e7e34; padding: 20px; border-radius: 16px; margin-bottom: 40px; text-align: center; font-weight: 500; box-shadow: 0 10px 20px rgba(30,126,52,0.1); border: 1px solid #c3e6cb;">
            ✨ <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
        <div style="text-align: center; background: white; padding: 60px 20px; border-radius: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <div style="font-size: 4rem; margin-bottom: 20px;">🧘</div>
            <h3 style="color: #333; margin-bottom: 15px; font-family: 'Playfair Display', serif;">Aún no has empezado tu viaje</h3>
            <p style="color: #777; margin-bottom: 25px;">Descubre nuestros tratamientos diseñados para tu salud.</p>
            <a href="index.php#servicios" style="background-color: #EB6250; color: white; padding: 15px 35px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; box-shadow: 0 10px 20px rgba(235,98,80,0.3);">Reservar mi primera cita</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 35px;">
            <?php foreach ($citas as $cita): 
                $timestamp = strtotime($cita['fecha_hora']);
                $fecha_dia = date('d', $timestamp);
                $fecha_mes = date('M', $timestamp);
                $hora_bonita = date('H:i', $timestamp);

                $color_principal = '#666'; $label_bg = '#f0f0f0';
                switch (strtolower($cita['estado'])) {
                    case 'pendiente': $color_principal = '#b7791f'; $label_bg = '#fff4e5'; break;
                    case 'completada': $color_principal = '#1e7e34'; $label_bg = '#e6f4ea'; break;
                    case 'cancelada': $color_principal = '#c5221f'; $label_bg = '#fce8e6'; break;
                }
            ?>
                <div style="position: relative;">
                    <div class="timeline-date"><?php echo $fecha_dia . " " . strtoupper($fecha_mes); ?></div>
                    
                    <div class="cita-card" style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 25px;">
                        
                        <div style="flex: 1; min-width: 280px;">
                            <div style="margin-bottom: 15px;">
                                <span style="background-color: <?php echo $label_bg; ?>; color: <?php echo $color_principal; ?>; padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                    ● <?php echo htmlspecialchars($cita['estado']); ?>
                                </span>
                            </div>
                            
                            <h3 style="color: #2D2D2D; margin: 0 0 8px 0; font-size: 1.4rem; font-family: 'Playfair Display', serif;"><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                            
                            <div style="display: flex; align-items: center; gap: 10px; color: #777; font-size: 0.95rem;">
                                <span style="font-size: 1.1rem;">👤</span>
                                <span>Con <strong><?php echo htmlspecialchars($cita['especialista'] ?? 'Staff LcQuiromasajes'); ?></strong></span>
                                <span style="margin: 0 5px;">•</span>
                                <span style="font-size: 1.1rem;">⏱️</span>
                                <span><?php echo $cita['duracion_minutos']; ?> min</span>
                            </div>
                        </div>

                        <div style="text-align: right; border-left: 1px path #eee; padding-left: 25px; min-width: 160px;">
                            <div style="color: #333; margin-bottom: 5px;">
                                <span style="font-size: 2rem; font-weight: 700; color: #EB6250;"><?php echo $hora_bonita; ?></span>
                            </div>
                            <div style="font-size: 1.1rem; font-weight: 600; color: #2D2D2D; margin-bottom: 15px;">
                                <?php echo number_format($cita['precio_final'], 2, ',', '.'); ?>€
                            </div>

                            <?php if (strtolower($cita['estado']) == 'pendiente'): ?>
                                <form action="cancelar_cita.php" method="POST" onsubmit="return confirm('¿Deseas cancelar tu momento de relax?');">
                                    <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                    <button type="submit" class="btn-cancelar" style="cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 500;">
                                        Cancelar Cita
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>   
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>