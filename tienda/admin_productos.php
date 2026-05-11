<?php
session_start();
require_once '../includes/db.php';

// Seguridad: Verificar que el usuario esté logueado (y asumiendo que tiene permisos de Admin)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Procesar la actualización rápida de stock
if (isset($_POST['actualizar_stock'])) {
    $id = $_POST['id_producto'];
    $nuevo_stock = $_POST['stock'];
    
    $stmt = $pdo->prepare("UPDATE Producto SET stock = ? WHERE id_producto = ?");
    $stmt->execute([$nuevo_stock, $id]);
    
    $_SESSION['mensaje_admin'] = "Stock actualizado correctamente.";
    header("Location: admin_productos.php");
    exit;
}

// Extraer mensaje temporal
$mensaje_admin = "";
if (isset($_SESSION['mensaje_admin'])) {
    $mensaje_admin = $_SESSION['mensaje_admin'];
    unset($_SESSION['mensaje_admin']);
}

// Obtener todo el inventario
$productos = $pdo->query("SELECT * FROM Producto ORDER BY id_producto DESC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div style="background-color: #FFF7EE; padding: 40px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; margin: 0;">Gestión de Inventario</h1>
</div>

<main style="max-width: 1000px; margin: 40px auto; padding: 0 20px; font-family: 'Poppins', sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #333; font-size: 1.5rem; margin: 0;">Catálogo Actual</h2>
        <a href="nuevo_producto.php" style="background: #EB6250; color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 600; box-shadow: 0 10px 20px rgba(235,98,80,0.2); transition: 0.3s;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
            + Añadir Producto
        </a>
    </div>

    <?php if ($mensaje_admin): ?>
        <div style="background: #e6f4ea; color: #1e7e34; padding: 15px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #c3e6cb;">
            ✨ <?= htmlspecialchars($mensaje_admin) ?>
        </div>
    <?php endif; ?>

    <div style="background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #FFF7EE; color: #886752;">
                <tr>
                    <th style="padding: 20px;">ID</th>
                    <th style="padding: 20px;">Nombre del Producto</th>
                    <th style="padding: 20px;">Precio</th>
                    <th style="padding: 20px;">Unidades (Stock)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 20px; color: #999;">#<?= $p['id_producto'] ?></td>
                    <td style="padding: 20px;">
                        <strong style="color: #333;"><?= htmlspecialchars($p['nombre']) ?></strong>
                        <div style="font-size: 0.8rem; color: #777; margin-top: 5px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($p['descripcion']) ?>
                        </div>
                    </td>
                    <td style="padding: 20px; font-weight: 600; color: #EB6250;">
                        <?= number_format($p['precio_actual'], 2, ',', '.') ?> €
                    </td>
                    <td style="padding: 20px;">
                        <form method="POST" action="admin_productos.php" style="margin: 0; display: flex; gap: 10px; align-items: center;">
                            <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                            <input type="number" name="stock" value="<?= $p['stock'] ?>" min="0" style="width: 70px; padding: 10px; border-radius: 10px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                            <button type="submit" name="actualizar_stock" style="background: #f1f3f4; border: none; padding: 10px 15px; border-radius: 10px; cursor: pointer; font-weight: 500; color: #333; transition: 0.2s;" onmouseover="this.style.background='#e2e6ea'" onmouseout="this.style.background='#f1f3f4'">
                                Guardar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($productos)): ?>
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #777;">No hay productos registrados en el sistema.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>