<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['guardar_producto'])) {
    $nombre = $_POST['nombre'];
    $desc = $_POST['descripcion'];
    $precio = $_POST['precio_actual'];
    $stock = $_POST['stock'];

    try {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio_actual, stock) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $desc, $precio, $stock]);
        
        $_SESSION['mensaje_admin'] = "El producto '$nombre' ha sido publicado en la tienda.";
        header("Location: admin_productos.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al guardar: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="contenedor-admin" style="text-align: center;">
    <div class="cabecera-admin">
        <h1>Nuevo Producto</h1>
    </div>
</div>

<main class="contenedor-principal">
    <div style="width: 100%; max-width: 600px;">
        <a href="admin_productos.php" class="enlace-volver">← Volver al inventario</a>

        <form method="POST" action="nuevo_producto.php" style="max-width: 100%; margin-top: 10px;">
            
            <?php if (isset($error)): ?>
                <div class="alerta-error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="grupo-entrada">
                <label>Nombre del Producto:</label>
                <input type="text" name="nombre" maxlength="50" required class="input-control">
            </div>

            <div class="grupo-entrada">
                <label>Descripción:</label>
                <textarea name="descripcion" maxlength="300" required class="input-control" style="height: 120px;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Precio:</label>
                    <input type="number" step="0.01" name="precio_actual" min="0" required class="input-control">
                </div>
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Stock Inicial:</label>
                    <input type="number" name="stock" min="0" value="0" required class="input-control">
                </div>
            </div>

            <button type="submit" name="guardar_producto" class="btn btn-primary boton-enviar">
                Publicar Producto
            </button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>