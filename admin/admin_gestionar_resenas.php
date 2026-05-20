<?php
session_start();
require_once '../includes/db.php';

// Seguridad
if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

// Lógica de aprobación o eliminación
if (isset($_GET['aprobar'])) {
    $stmt = $pdo->prepare("UPDATE Opiniones SET visible = 1 WHERE id = ?");
    $stmt->execute([$_GET['aprobar']]);
}
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM Opiniones WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
}

include '../includes/header.php';
$resenas = $pdo->query("SELECT * FROM Opiniones ORDER BY id DESC")->fetchAll();
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
                <td><?= htmlspecialchars($r['nombre_cliente']) ?></td>
                <td><?= htmlspecialchars($r['comentario']) ?></td>
                <td><?= $r['visible'] ? '✅ Visible' : '⏳ Pendiente' ?></td>
                <td>
                    <?php if (!$r['visible']): ?>
                        <a href="?aprobar=<?= $r['id'] ?>" class="btn btn-sm btn-success">Aprobar</a>
                    <?php endif; ?>
                    <a href="?eliminar=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Borrar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>