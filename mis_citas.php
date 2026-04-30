<?php
session_start();
require_once 'includes/db.php';

// 1. SEGURIDAD: Solo usuarios logueados pueden ver esto
if (!isset($_SESSION['id_perfil'])) {
    header("Location: login/index.html");
    exit;
}

$id_perfil = $_SESSION['id_perfil'];

// Comprobar si hay mensaje de éxito desde reservar.php
$mensaje_exito = "";
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje_exito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']); // Lo borramos para que no salga al recargar
}

try {
    // 2. Consulta SQL avanzada (JOINs) para sacar toda la info útil
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

<main class="contenedor-principal" style="flex-direction: column; align-items: stretch; width: 100%; max-width: 800px; margin: 40px auto; padding: 0 20px; min-height: 40vh;">    
    
    <!-- Mostrar alerta de éxito si acaba de reservar -->
    <?php if ($mensaje_exito): ?>
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 12px; margin-bottom: 30px; text-align: center; font-weight: 500;">
            <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
        <div style="text-align: center; background: white; padding: 50px 20px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ddd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 20px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <h3 style="color: #333; margin-bottom: 10px;">Aún no tienes citas</h3>
            <p style="color: #777; margin-bottom: 25px;">Anímate a reservar tu primer tratamiento con nosotros. ¡Tu cuerpo te lo agradecerá!</p>
            <a href="index.php#servicios" class="btn btn-primary" style="background-color: #EB6250; color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 600;">Ver Tratamientos</a>
        </div>
    <?php else: ?>
        
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($citas as $cita): 
                
                // Formatear la fecha y la hora para que se lea bien en español
                $timestamp = strtotime($cita['fecha_hora']);
                $fecha_bonita = date('d/m/Y', $timestamp);
                $hora_bonita = date('H:i', $timestamp);

                // Determinar los colores de la etiqueta según el estado
                $color_fondo = '#f0f0f0';
                $color_texto = '#666';
                
                switch (strtolower($cita['estado'])) {
                    case 'pendiente':
                        $color_fondo = '#fff4e5';
                        $color_texto = '#b7791f';
                        break;
                    case 'confirmada':
                        $color_fondo = '#e3f2fd';
                        $color_texto = '#0d47a1';
                        break;
                    case 'completada':
                        $color_fondo = '#e6f4ea';
                        $color_texto = '#1e7e34';
                        break;
                    case 'cancelada':
                        $color_fondo = '#fce8e6';
                        $color_texto = '#c5221f';
                        break;
                }
            ?>
            
                <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px; border-left: 5px solid <?php echo $color_texto; ?>;">
                    
                    <div style="flex: 1; min-width: 250px;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                            <span style="background-color: <?php echo $color_fondo; ?>; color: <?php echo $color_texto; ?>; padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;">
                                <?php echo htmlspecialchars($cita['estado']); ?>
                            </span>
                            <span style="color: #888; font-size: 0.9rem; display: flex; align-items: center; gap: 5px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <?php echo $cita['duracion_minutos']; ?> min
                            </span>
                        </div>
                        <h3 style="color: #333; margin-bottom: 5px; font-size: 1.2rem;"><?php echo htmlspecialchars($cita['servicio']); ?></h3>
                        <p style="color: #666; font-size: 0.95rem; margin: 0;">
                            Especialista: <strong><?php echo htmlspecialchars($cita['especialista'] ?? 'Pendiente de asignar'); ?></strong>
                        </p>
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
        
        <div style="text-align: center; margin-top: 40px;">
            <p style="color: #888; font-size: 0.85rem;">Para cancelar o modificar una cita, por favor contacta con nosotros por teléfono.</p>
        </div>
        
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>