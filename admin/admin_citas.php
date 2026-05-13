<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id_cita']) && isset($_GET['nuevo_estado'])) {
    try {
        $updateStmt = $pdo->prepare("UPDATE Citas SET estado = ? WHERE id_cita = ?");
        $updateStmt->execute([$_GET['nuevo_estado'], $_GET['id_cita']]);
        header("Location: admin_citas.php?msg=ok");
        exit;
    } catch (PDOException $e) {
        $error_estado = "Error al actualizar la cita.";
    }
}

$rol_actual = $_SESSION['rol'];
$id_perfil_actual = $_SESSION['user_id'];

try {
    // AÑADIDO: notas_cliente en la primera línea del SELECT
    $sql = "SELECT 
                id_cita, fecha_hora, estado, precio_final, notas_cliente,
                (SELECT nombre FROM Usuario WHERE id_perfil = c.id_perfil) AS cliente,
                (SELECT apellido FROM Usuario WHERE id_perfil = c.id_perfil) AS cliente_apellido,
                (SELECT telefono FROM Usuario WHERE id_perfil = c.id_perfil) AS cliente_telefono,
                (SELECT nombre FROM Servicios WHERE id_servicio = c.id_servicio) AS servicio,
                (SELECT nombre FROM Trabajadores WHERE id_trabajador = c.id_trabajador) AS especialista
            FROM Citas c";
            
    if ($rol_actual === 'trabajador') {
        $sql .= " WHERE id_trabajador = (SELECT id_trabajador FROM Trabajadores WHERE id_perfil = ?)";
        $sql .= " ORDER BY fecha_hora ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil_actual]);
    } else {
        $sql .= " ORDER BY fecha_hora ASC";
        $stmt = $pdo->query($sql);
    }

    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al consultar citas: " . $e->getMessage());
    $citas = [];
}

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver al Panel de Control</a>
    </div>

    <div style="margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin: 0;">Gestión de Citas</h1>
        <p style="color: #886752;">Agenda de tratamientos y terapias de LC Quiromasajes.</p>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #c8e6c9;">
            ✓ Cita actualizada correctamente.
        </div>
    <?php endif; ?>

    <div style="background: #FFFFFF; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); overflow: hidden; border: none;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Fecha y Hora</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Cliente</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Servicio</th>
                    <?php if($rol_actual === 'admin'): ?>
                        <th style="padding: 20px; color: #886752; font-weight: 600;">Especialista</th>
                    <?php endif; ?>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Estado</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Total</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($citas)): ?>
                    <tr>
                        <td colspan="7" style="padding: 50px; text-align: center; color: #999;">No hay citas programadas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($citas as $cita): ?>
                    
                        <tr style="border-bottom: 1px solid #f9f9f9; transition: background 0.3s;" onmouseover="this.style.background='#fffcf8'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px 20px; color: #666; font-size: 0.9rem;">
                                <strong><?php echo date('d/m/Y', strtotime($cita['fecha_hora'])); ?></strong><br>
                                <?php echo date('H:i', strtotime($cita['fecha_hora'])); ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: #333;">
                                    <?php echo htmlspecialchars($cita['cliente'] . " " . ($cita['cliente_apellido'] ?? '')); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: #EB6250;">
                                    <?php echo htmlspecialchars($cita['cliente_telefono'] ?? ''); ?>
                                </div>
                            </td>
                            
                            <td style="padding: 15px 20px; color: #555;">
                                <span style="background: #f0f0f0; padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; display: inline-block;">
                                    <?php echo htmlspecialchars($cita['servicio']); ?>
                                </span>
                                
                                <?php if (!empty($cita['notas_cliente'])): ?>
                                    <details style="margin-top: 10px; font-size: 0.85rem; background: #FFF7EE; padding: 8px; border-radius: 8px; border: 1px dashed #EB6250;">
                                        <summary style="cursor: pointer; color: #EB6250; font-weight: 600; outline: none;">
                                            📝 Ver notas
                                        </summary>
                                        <p style="margin: 8px 0 0 0; color: #666; font-style: italic;">
                                            "<?php echo nl2br(htmlspecialchars($cita['notas_cliente'])); ?>"
                                        </p>
                                    </details>
                                <?php endif; ?>
                            </td>
                            
                            <?php if($rol_actual === 'admin'): ?>
                                <td style="padding: 15px 20px; color: #666; font-size: 0.9rem;">
                                    <?php echo htmlspecialchars($cita['especialista'] ?? 'Sin asignar'); ?>
                                </td>
                            <?php endif; ?>
                            <td style="padding: 15px 20px;">
                                <?php 
                                    $bg = '#F39C12';
                                    if ($cita['estado'] == 'Completado') $bg = '#27AE60'; 
                                    if ($cita['estado'] == 'Cancelado') $bg = '#E74C3C'; 
                                ?>
                                <span style="background: <?php echo $bg; ?>; color: white; padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                    <?php echo $cita['estado']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 20px; font-weight: 700; color: #333;">
                                <?php echo number_format($cita['precio_final'], 2); ?>€
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <?php if ($cita['estado'] == 'Pendiente'): ?>
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="admin_citas.php?id_cita=<?php echo $cita['id_cita']; ?>&nuevo_estado=Completado" 
                                           title="Completar" 
                                           style="background: #27AE60; color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; font-weight: bold;">✓</a>
                                        
                                        <a href="admin_citas.php?id_cita=<?php echo $cita['id_cita']; ?>&nuevo_estado=Cancelado" 
                                           title="Cancelar" 
                                           onclick="return confirm('¿Seguro que quieres cancelar esta cita?')"
                                           style="background: #E74C3C; color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; font-weight: bold;">✕</a>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #ccc; font-size: 0.75rem;">Finalizada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>