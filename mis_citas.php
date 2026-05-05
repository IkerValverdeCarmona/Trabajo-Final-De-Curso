<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['id_perfil'])) {
    header("Location: login/index.html");
    exit;
}

$id_perfil = $_SESSION['id_perfil'];

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

<div style="background-color: #FFF7EE; padding: 40px 20px; text-align: center;">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250;">Mis Citas</h1>
    <p style="color: #666; max-width: 600px; margin: 0 auto;">Revisa tus próximas visitas o consulta tu historial de tratamientos.</p>
</div>

<main class="contenedor-principal" style="width: 100%; max-width: 800px; margin: 40px auto; padding: 0 20px; min-height: 40vh;">    
    
    <?php if ($mensaje_exito): ?>
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 12px; margin-bottom: 30px; text-align: center; font-weight: 500;">
            <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
        <div style="text-align: center; background: white; padding: 50px 20px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <h3 style="color: #333; margin-bottom: 10px;">Aún no tienes citas</h3>
            <a href="index.php#servicios" class="btn btn-primary" style="background-color: #EB6250; color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 600;">Ver Tratamientos</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($citas as $cita): 
                $timestamp = strtotime($cita['fecha_hora']);
                $fecha_bonita = date('d/m/Y', $timestamp);
                $hora_bonita = date('H:i', $timestamp);

                $color_fondo = '#f0f0f0'; $color_texto = '#666';
                switch (strtolower($cita['estado'])) {
                    case 'pendiente': $color_fondo = '#fff4e5'; $color_texto = '#b7791f'; break;
                    case 'confirmada': $color_fondo = '#e3f2fd'; $color_texto = '#0d47a1'; break;
                    case 'completada': $color_fondo = '#e6f4ea'; $color_texto = '#1e7e34'; break;
                    case 'cancelada': $color_fondo = '#fce8e6'; $color_texto = '#c5221f'; break;
                }
            ?>
                <!-- Tarjeta de Cita -->
                <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px; border-left: 5px solid <?php echo $color_texto; ?>;">
                    
                    <div style="flex: 1; min-width: 250px;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                            <span style="background-color: <?php echo $color_fondo; ?>; color: <?php echo $color_texto; ?>; padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;">
                                <?php echo htmlspecialchars($cita['estado']); ?>
                            </span>
                        </div>
                        <h3 style="color: #333; margin-bottom: 5px; font-size: 1.2rem;"><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                        <p style="color: #666; font-size: 0.95rem; margin-bottom: 10px;">
                            Especialista: <strong><?php echo htmlspecialchars($cita['especialista'] ?? 'Pendiente de asignar'); ?></strong>
                        </p>

                        <!-- Botón Cancelar (Dentro de la tarjeta) -->
                        <?php if (strtolower($cita['estado']) == 'pendiente'): ?>
                            <form action="cancelar_cita.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');">
                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                <button type="submit" style="background: none; border: none; color: #c5221f; font-size: 0.85rem; cursor: pointer; text-decoration: underline; padding: 0;">
                                    Cancelar cita
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div style="text-align: right; min-width: 150px;">
                        <p style="color: #EB6250; font-size: 1.2rem; font-weight: 700; margin-bottom: 5px;">
                            <?php echo $fecha_bonita; ?> <br>
                            <span style="color: #333; font-size: 1.5rem;"><?php echo $hora_bonita; ?></span>
                        </p>
                        <p style="color: #777; font-size: 0.9rem; margin: 0;">Total: <?php echo number_format($cita['precio_final'], 2, ',', '.'); ?>€</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>   
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>