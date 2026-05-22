<?php
session_start();
require_once '../includes/db.php';

// Seguridad
if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../public/index.php");
    exit;
}

// Lógica de aprobación o eliminación
if (isset($_GET['aprobar'])) {
    // CORREGIDO: id_opinion
    $stmt = $pdo->prepare("UPDATE Opiniones SET visible = 1 WHERE id_opinion = ?");
    $stmt->execute([$_GET['aprobar']]);
    header("Location: admin_gestionar_resenas.php"); // Redirección para limpiar la URL
    exit;
}
if (isset($_GET['eliminar'])) {
    // CORREGIDO: id_opinion
    $stmt = $pdo->prepare("DELETE FROM Opiniones WHERE id_opinion = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: admin_gestionar_resenas.php");
    exit;
}

include '../includes/header.php';
$resenas = $pdo->query("SELECT * FROM Opiniones ORDER BY id_opinion DESC")->fetchAll();
?>

<div class="contenedor-admin" style="padding: 40px;">
    <h1>Moderación de Reseñas</h1>
    <table class="table" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr><th>Cliente</th><th>Comentario</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($resenas as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id_perfil']) ?></td> <td><?= htmlspecialchars($r['comentario']) ?></td>
                <td><?= $r['visible'] ? '✅ Visible' : '⏳ Pendiente' ?></td>
                <td>
                    <?php if (!$r['visible']): ?>
                        <a href="?aprobar=<?= $r['id_opinion'] ?>" class="btn btn-sm btn-success">Aprobar</a>
                    <?php endif; ?>
                    <a href="?eliminar=<?= $r['id_opinion'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Borrar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>