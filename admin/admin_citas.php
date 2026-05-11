<?php
session_start();

// 1. CONEXIÓN A LA BASE DE DATOS (Vital para evitar el Error 500)
require_once '../includes/db.php';

// 2. SEGURIDAD: Comprobamos que solo entren trabajadores o el admin
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

// 3. PROCESAR EL CAMBIO DE ESTADO (Completado, Cancelado...)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_estado']) && isset($_POST['id_cita'])) {
    try {
        $updateStmt = $pdo->prepare("UPDATE Citas SET estado = ? WHERE id_cita = ?");
        $updateStmt->execute([$_POST['nuevo_estado'], $_POST['id_cita']]);
        
        // Recargamos la página para que se actualicen los colores en la tabla
        header("Location: admin_citas.php");
        exit;
    } catch (PDOException $e) {
        $error_estado = "No se pudo actualizar el estado de la cita.";
    }
}

// 4. Obtener datos del usuario conectado
$rol_actual = $_SESSION['rol'];
$id_perfil_actual = $_SESSION['user_id'];

try {
    // Definimos la base de la consulta
    $sql = "SELECT 
                id_cita, fecha_hora, estado, precio_final,
                (SELECT nombre FROM Usuario WHERE id_perfil = Citas.id_perfil) AS cliente,
                (SELECT nombre FROM Servicios WHERE id_servicio = Citas.id_servicio) AS servicio,
                (SELECT nombre FROM Trabajadores WHERE id_trabajador = Citas.id_trabajador) AS especialista
            FROM Citas";

    // APLICAR FILTRO SEGÚN ROL
    if ($rol_actual === 'trabajador') {
        // Buscamos las citas donde el trabajador sea el que está logueado
        // Nota: Asumimos que en la tabla Trabajadores tienes el id_perfil para vincularlos
        $sql .= " WHERE id_trabajador = (SELECT id_trabajador FROM Trabajadores WHERE id_perfil = ?)";
        $sql .= " ORDER BY fecha_hora DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil_actual]);
    } else {
        // Si es admin (Iker), ve todas las citas
        $sql .= " ORDER BY fecha_hora DESC";
        $stmt = $pdo->query($sql);
    }

    $citas = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Error al consultar citas: " . $e->getMessage());
    $citas = [];
}

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">

<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="color: #666; text-decoration: none; font-family: 'Poppins', sans-serif; font-size: 0.9rem;">← Volver al Panel de Control</a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif; color: #EB6250;">Gestión de Citas</h1>
    </div>

    <?php if (isset($error_estado)): ?>
        <div style="background: #fce8e6; color: #c5221f; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            ⚠️ <?php echo $error_estado; ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 20px; border: none; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-family: 'Poppins', sans-serif;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px;">Fecha y Hora</th>
                    <th style="padding: 20px;">Cliente</th>
                    <th style="padding: 20px;">Servicio</th>
                    <th style="padding: 20px;">Especialista</th>
                    <th style="padding: 20px;">Estado</th>
                    <th style="padding: 20px;">Total</th>
                    <th style="padding: 20px;">Acciones</th> 
                </tr>
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
                            <td style="padding: 15px 20px; font-weight: 700; color: #EB6250;"><?php echo $cita['precio_final']; ?>€</td>
                            
                            <td style="padding: 15px 20px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <form action="admin_citas.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
                                        <select name="nuevo_estado" onchange="this.form.submit()" style="padding: 5px; border-radius: 8px; border: 1px solid #ddd; font-size: 0.8rem; cursor: pointer;">
                                            <option value="" disabled selected>Estado...</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Completado">Completado</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </form>
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