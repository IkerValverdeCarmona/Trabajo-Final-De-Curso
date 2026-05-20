<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['aprobar'])) {
    $pdo->prepare("UPDATE Opiniones SET visible = 1 WHERE id_opinion = ?")->execute([$_GET['aprobar']]);
}

$resenas = $pdo->query("SELECT O.*, P.email FROM Opiniones O JOIN Perfil P ON O.id_perfil = P.id_perfil")->fetchAll();
?>

<table>
    <tr><th>Usuario</th><th>Comentario</th><th>Acciones</th></tr>
    <?php foreach ($resenas as $r): ?>
    <tr>
        <td><?= $r['email'] ?></td>
        <td><?= $r['comentario'] ?></td>
        <td>
            <?php if (!$r['visible']): ?>
                <a href="?aprobar=<?= $r['id_opinion'] ?>">Aprobar</a>
            <?php else: ?>
                <span>Publicado</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>