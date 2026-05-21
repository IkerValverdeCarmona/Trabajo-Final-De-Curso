<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

// Procesar activación / desactivación (Baja Lógica)
if (isset($_GET['toggle_activo']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nuevo_estado = $_GET['toggle_activo'];
    
    $stmt = $pdo->prepare("UPDATE Servicios SET activo = ? WHERE id_servicio = ?");
    $stmt->execute([$nuevo_estado, $id]);
    
    $_SESSION['mensaje_admin'] = "Estado del tratamiento actualizado.";
    header("Location: admin_servicios.php");
    exit;
}

$mensaje_admin = "";
if (isset($_SESSION['mensaje_admin'])) {
    $mensaje_admin = $_SESSION['mensaje_admin'];
    unset($_SESSION['mensaje_admin']);
}

// Obtener todos los servicios
$servicios = $pdo->query("SELECT * FROM Servicios ORDER BY id_servicio DESC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="contenedor-admin">
    
    <a href="index.php" class="enlace-volver">← Volver al Panel de Control</a>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div class="cabecera-admin" style="margin-bottom: 0;">
            <h1>Gestión de Tratamientos</h1>
            <p>Catálogo de terapias y servicios del centro.</p>
        </div>
        <a href="nuevo_servicio.php" class="btn btn-primary">+ Añadir Tratamiento</a>
    </div>

    <?php if ($mensaje_admin): ?>
        <div class="alerta-exito">✅ <?= htmlspecialchars($mensaje_admin) ?></div>
    <?php endif; ?>

    <div class="tarjeta-admin">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Tratamiento</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th style="text-align: right;">Estado y Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicios as $s): ?>
                <tr>
                    <td>
                        <div class="texto-destacado"><?= htmlspecialchars($s['nombre']) ?></div>
                        <div style="font-size: 0.8rem; color: #777; margin-top: 5px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($s['descripcion']) ?>
                        </div>
                    </td>
                    <td class="texto-destacado">
                        ⏱ <?= $s['duracion_minutos'] ?> min
                    </td>
                    <td class="texto-destacado" style="color: var(--color-primary);">
                        <?= number_format($s['precio_actual'], 2, ',', '.') ?> €
                    </td>
                    <td>
                        <div style="display: flex; gap: 15px; align-items: center; justify-content: flex-end;">
                            <?php if ($s['activo'] == 1): ?>
                                <span class="etiqueta-estado estado-completado">Público</span>
                                <a href="admin_servicios.php?toggle_activo=0&id=<?= $s['id_servicio'] ?>" class="btn-accion btn-ko" title="Ocultar de la tienda" onclick="return confirm('¿Ocultar este tratamiento a los clientes?');">👁️‍🗨️</a>
                            <?php else: ?>
                                <span class="etiqueta-estado estado-cancelado">Oculto</span>
                                <a href="admin_servicios.php?toggle_activo=1&id=<?= $s['id_servicio'] ?>" class="btn-accion btn-ok" title="Publicar en tienda">👁️</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($servicios)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay tratamientos registrados en el sistema.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>