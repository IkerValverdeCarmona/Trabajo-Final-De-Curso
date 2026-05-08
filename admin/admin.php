<?php
session_start();

// 1. CORRECCIÓN DE RUTA: Salir de la carpeta admin para encontrar includes
require_once '../includes/db.php'; 

// 2. CORRECCIÓN DE SEGURIDAD: Usar la variable de sesión 'rol' que definimos en el login
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

// Consultamos las citas con JOINs
try {
    $sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.precio_final, 
                   u.nombre as cliente, s.nombre as servicio, t.nombre as especialista
            FROM Citas c
            JOIN Usuario u ON c.id_perfil = u.id_perfil
            JOIN Servicios s ON c.id_servicio = s.id_servicio
            LEFT JOIN Trabajadores t ON c.id_trabajador = t.id_trabajador
            ORDER BY c.fecha_hora DESC";

    $citas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
     // Si hay error, lo mostramos (en producción deberíamos loguearlo en vez de mostrarlo)
     error_log("Error al consultar citas: " . $e->getMessage());
     $error = "No se pudieron cargar las citas. Inténtalo de nuevo más tarde.";
     $citas = [];
}

// 3. CORRECCIÓN DE RUTA: Salir para incluir el header
include '../includes/header.php';
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif;">Panel de Control</h1>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 20px; border: none; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
    <tr>
        <th style="padding: 20px;">Fecha y Hora</th>
        <th style="padding: 20px;">Cliente</th>
        <th style="padding: 20px;">Servicio</th>
        <th style="padding: 20px;">Especialista</th>
        <th style="padding: 20px;">Estado</th>
        <th style="padding: 20px;">Total</th>
        <th style="padding: 20px;">Acciones</th> </tr>
</thead>
<tbody>
    <?php if (empty($citas)): ?>
        <tr>
            <td colspan="7" style="padding: 40px; text-align: center; color: #666;">No hay citas registradas.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($citas as $cita): ?>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 15px 20px;"><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></td>
                <td style="padding: 15px 20px; font-weight: 500;"><?php echo htmlspecialchars($cita['cliente']); ?></td>
                <td style="padding: 15px 20px;"><?php echo htmlspecialchars($cita['servicio']); ?></td>
                <td style="padding: 15px 20px;"><?php echo htmlspecialchars($cita['especialista'] ?? 'Sin asignar'); ?></td>
                <td style="padding: 15px 20px;">
                    <span style="padding: 5px 12px; border-radius: 50px; font-size: 0.85rem; 
                               background: <?php echo $cita['estado'] === 'Completado' ? '#e6f4ea' : ($cita['estado'] === 'Cancelado' ? '#ffebee' : '#fff4e5'); ?>;
                               color: <?php echo $cita['estado'] === 'Completado' ? '#1e7e34' : ($cita['estado'] === 'Cancelado' ? '#c62828' : '#b7791f'); ?>;">
                        <?php echo htmlspecialchars($cita['estado']); ?>
                    </span>
                </td>
                <td style="padding: 15px 20px; font-weight: 700;"><?php echo $cita['precio_final']; ?>€</td>
                
                <td style="padding: 15px 20px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <form action="gestionar_cita.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
                            <select name="nuevo_estado" onchange="this.form.submit()" style="padding: 5px; border-radius: 8px; border: 1px solid #ddd; font-size: 0.8rem; cursor: pointer;">
                                <option value="" disabled selected>Estado...</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Completado">Completado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </form>
                        
                        <a href="eliminar_cita.php?id=<?php echo $cita['id_cita']; ?>" 
                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita permanentemente?')"
                           style="text-decoration: none; font-size: 1.1rem;" title="Eliminar cita">
                           🗑️
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>        
</table>
    </div>
</div>

<?php 
// 4. CORRECCIÓN DE RUTA: Salir para incluir el footer
include '../includes/footer.php'; 
?>