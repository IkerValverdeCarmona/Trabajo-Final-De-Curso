<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../public/index.php");
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

// Procesar el borrado de un producto
if (isset($_POST['borrar_producto'])) {
    $id_borrar = $_POST['id_producto'];
    try {
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id_producto = ?");
        $stmt->execute([$id_borrar]);
        $_SESSION['mensaje_admin'] = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "No puedes borrar un producto que ya tiene ventas históricas. Te recomendamos poner su stock a 0.";
    }
    header("Location: admin_productos.php");
    exit;
}

$mensaje_admin = "";
if (isset($_SESSION['mensaje_admin'])) {
    $mensaje_admin = $_SESSION['mensaje_admin'];
    unset($_SESSION['mensaje_admin']);
}

$mensaje_error = "";
if (isset($_SESSION['mensaje_error'])) {
    $mensaje_error = $_SESSION['mensaje_error'];
    unset($_SESSION['mensaje_error']);
}

$productos = $pdo->query("SELECT * FROM Producto ORDER BY id_producto DESC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="contenedor-admin">
    
    <a href="index.php" class="enlace-volver">← Volver al Panel de Control</a>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div class="cabecera-admin" style="margin-bottom: 0;">
            <h1>Gestión de Tienda</h1>
            <p>Control de inventario y catálogo de productos</p>
        </div>
        <a href="nuevo_producto.php" class="btn btn-primary">+ Añadir Producto</a>
    </div>

    <?php if ($mensaje_admin): ?>
        <div class="alerta-exito">✅ <?= htmlspecialchars($mensaje_admin) ?></div>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <div class="alerta-error">⚠️ <?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>

    <div class="tarjeta-admin">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th style="text-align: right;">Stock y Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td style="color: #999;">#<?= $p['id_producto'] ?></td>
                    <td>
                        <div class="texto-destacado"><?= htmlspecialchars($p['nombre']) ?></div>
                        <div style="font-size: 0.8rem; color: #777; margin-top: 5px; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($p['descripcion']) ?>
                        </div>
                    </td>
                    <td class="texto-destacado" style="color: var(--color-primary);">
                        <?= number_format($p['precio_actual'], 2, ',', '.') ?> €
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end;">
                            <form method="POST" action="admin_productos.php" style="margin: 0; display: flex; gap: 10px; align-items: center; background: none; padding: 0; box-shadow: none;">
                                <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                <input type="number" name="stock" value="<?= $p['stock'] ?>" min="0" class="input-radius" style="width: 80px; padding: 8px;">
                                <button type="submit" name="actualizar_stock" class="btn btn-secondary" style="padding: 8px 15px;">Guardar</button>
                            </form>
                            
                            <form method="POST" action="admin_productos.php" style="margin: 0; background: none; padding: 0; box-shadow: none;" onsubmit="return confirm('¿Seguro que quieres borrar este producto?');">
                                <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                <button type="submit" name="borrar_producto" title="Eliminar producto" class="btn-accion btn-ko">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($productos)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay productos registrados en la tienda.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>