<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
require_once 'includes/header.php';
?>
<div class="container" style="max-width: 600px; margin: 50px auto; padding: 20px;">
    <h2>Tu opinión cuenta</h2>
    <form action="procesar_resena.php" method="POST">
        <div class="form-group">
            <label>Puntuación (1-5):</label>
            <input type="number" name="puntuacion" min="1" max="5" required class="form-control">
        </div>
        <div class="form-group">
            <label>Comentario:</label>
            <textarea name="comentario" required class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Enviar reseña</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>