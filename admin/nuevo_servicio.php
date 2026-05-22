<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../public/index.php");
    exit;
}

if (isset($_POST['guardar_servicio'])) {
    $nombre = $_POST['nombre'];
    $desc = $_POST['descripcion'];
    $precio = $_POST['precio_actual'];
    $duracion = $_POST['duracion_minutos'];

    try {
        // Por defecto, lo guardamos como activo (1)
        $sql = "INSERT INTO Servicios (nombre, descripcion, precio_actual, duracion_minutos, activo) VALUES (?, ?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $desc, $precio, $duracion]);
        
        $_SESSION['mensaje_admin'] = "El tratamiento '$nombre' se ha añadido al catálogo.";
        header("Location: admin_servicios.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al guardar: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="contenedor-admin" style="text-align: center;">
    <div class="cabecera-admin">
        <h1>Nuevo Tratamiento</h1>
    </div>
</div>

<main class="contenedor-principal">
    <div style="width: 100%; max-width: 600px;">
        <a href="admin_servicios.php" class="enlace-volver">← Volver al catálogo</a>

        <form method="POST" action="nuevo_servicio.php" style="max-width: 100%; margin-top: 10px;">
            
            <?php if (isset($error)): ?>
                <div class="alerta-error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="grupo-entrada">
                <label>Nombre del Tratamiento:</label>
                <input type="text" name="nombre" maxlength="100" placeholder="Ej. Masaje Descontracturante" required class="input-control">
            </div>

            <div class="grupo-entrada">
                <label>Descripción:</label>
                <textarea name="descripcion" maxlength="500" placeholder="Explica en qué consiste el tratamiento..." required class="input-control" style="height: 120px;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Precio Final (€):</label>
                    <input type="number" step="0.01" name="precio_actual" min="0" placeholder="0.00" required class="input-control">
                </div>
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Duración (Minutos):</label>
                    <input type="number" name="duracion_minutos" min="5" step="5" value="60" required class="input-control">
                </div>
            </div>

            <button type="submit" name="guardar_servicio" class="btn btn-primary boton-enviar">
                Añadir al Catálogo
            </button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>