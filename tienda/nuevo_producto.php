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

<div style="background-color: #FFF7EE; padding: 40px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; margin: 0;">Nuevo Producto</h1>
</div>

<main style="max-width: 600px; margin: 40px auto; padding: 0 20px; font-family: 'Poppins', sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="admin_productos.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver al inventario</a>
    </div>

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
        
        <?php if (isset($error)): ?>
            <div style="background: #fce8e6; color: #c5221f; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="nuevo_producto.php" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div>
                <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Nombre del Producto:</label>
                <input type="text" name="nombre" maxlength="50" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
            </div>

            <div>
                <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Descripción:</label>
                <textarea name="descripcion" maxlength="300" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif; height: 120px; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Precio:</label>
                    <input type="number" step="0.01" name="precio_actual" min="0" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Stock Inicial:</label>
                    <input type="number" name="stock" min="0" value="0" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                </div>
            </div>

            <button type="submit" name="guardar_producto" style="background: #EB6250; color: white; border: none; padding: 16px; border-radius: 50px; font-weight: 600; font-size: 1rem; cursor: pointer; font-family: 'Poppins', sans-serif; margin-top: 10px; transition: 0.3s;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
                Publicar Producto
            </button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>