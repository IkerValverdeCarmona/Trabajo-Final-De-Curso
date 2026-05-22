<?php 
session_start(); 
require_once '../includes/db.php'; 
require_once '../includes/header.php';

// Obtener reseñas aprobadas (visible = 1)
$resenas = $pdo->query("SELECT * FROM Opiniones WHERE visible = 1 ORDER BY fecha_publicacion DESC")->fetchAll();
?>

<div class="container" style="padding: 60px 20px;">
    <h1 style="text-align: center; font-family: 'Playfair Display'; color: #EB6250;">Opiniones de nuestros clientes</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div style="max-width: 600px; margin: 40px auto; background: #FFF7EE; padding: 30px; border-radius: 20px;">
            <h3 style="margin-bottom: 20px;">¿Cómo ha sido tu experiencia?</h3>
            <form action="<?php echo PAGE_URL; ?>procesar_resena.php" method="POST">
                <div class="mb-3">
                    <label>Puntuación (1-5):</label>
                    <input type="number" name="puntuacion" min="1" max="5" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Tu comentario:</label>
                    <textarea name="comentario" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Reseña</button>
            </form>
        </div>
    <?php else: ?>
        <p style="text-align: center; margin: 40px;">Debes <a href="<?php echo BASE_URL; ?>auth/login.php">iniciar sesión</a> para dejar una reseña.</p>
    <?php endif; ?>

    <hr style="margin: 60px 0;">

    <div class="row">
        <?php foreach ($resenas as $r): ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 20px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <p>"<?php echo htmlspecialchars($r['comentario']); ?>"</p>
                    <div style="color: #FFD700;"><?php echo str_repeat('★', (int)$r['puntuacion']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>