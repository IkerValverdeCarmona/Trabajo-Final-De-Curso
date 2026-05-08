<?php
session_start();
require_once 'includes/db.php';

// Seguridad: Solo administradores
if (!isset($_SESSION['permiso']) || $_SESSION['permiso'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Consultamos las citas con JOINs para ver nombres reales
$sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.precio_final, 
               u.nombre as cliente, s.nombre as servicio, t.nombre as especialista
        FROM Citas c
        JOIN Usuario u ON c.id_perfil = u.id_perfil
        JOIN Servicios s ON c.id_servicio = s.id_servicio
        LEFT JOIN Trabajadores t ON c.id_trabajador = t.id_trabajador
        ORDER BY c.fecha_hora DESC";

$citas = $pdo->query($sql)->fetchAll();
include 'includes/header.php';
?>

<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif;">Panel de Administración</h1>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-outline-primary btn-sm">Nuevo Servicio</button>
            <button class="btn btn-primary btn-sm">Descargar Informe</button>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px;">Fecha y Hora</th>
                    <th style="padding: 20px;">Cliente</th>
                    <th style="padding: 20px;">Servicio</th>
                    <th style="padding: 20px;">Especialista</th>
                    <th style="padding: 20px;">Estado</th>
                    <th style="padding: 20px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px 20px;"><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></td>
                        <td style="padding: 15px 20px; font-weight: 500;"><?php echo htmlspecialchars($cita['cliente']); ?></td>
                        <td style="padding: 15px 20px;"><?php echo htmlspecialchars($cita['servicio']); ?></td>
                        <td style="padding: 15px 20px;"><?php echo htmlspecialchars($cita['especialista'] ?? 'Sin asignar'); ?></td>
                        <td style="padding: 15px 20px;">
                            <span style="padding: 5px 12px; border-radius: 50px; font-size: 0.85rem; 
                                       background: <?php echo $cita['estado'] === 'Completado' ? '#e6f4ea' : '#fff4e5'; ?>;
                                       color: <?php echo $cita['estado'] === 'Completado' ? '#1e7e34' : '#b7791f'; ?>;">
                                <?php echo $cita['estado']; ?>
                            </span>
                        </td>
                        <td style="padding: 15px 20px; font-weight: 700;"><?php echo $cita['precio_final']; ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>