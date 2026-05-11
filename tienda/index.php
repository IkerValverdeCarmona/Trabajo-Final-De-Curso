<?php
session_start();
require_once '../includes/db.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Lógica para añadir al carrito
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock_max = $_POST['stock_max'];
    
    // Sumar 1 si ya existe, sin pasarse del stock disponible
    if (isset($_SESSION['carrito'][$id])) {
        if ($_SESSION['carrito'][$id]['cantidad'] < $stock_max) {
            $_SESSION['carrito'][$id]['cantidad']++;
            $_SESSION['mensaje_tienda'] = "¡Has añadido otra unidad de $nombre!";
        } else {
            $_SESSION['mensaje_tienda'] = "No hay más stock disponible de $nombre.";
        }
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => 1
        ];
        $_SESSION['mensaje_tienda'] = "¡$nombre añadido a tu cesta!";
    }
    header("Location: index.php");
    exit;
}

$mensaje_tienda = "";
if (isset($_SESSION['mensaje_tienda'])) {
    $mensaje_tienda = $_SESSION['mensaje_tienda'];
    unset($_SESSION['mensaje_tienda']);
}

try {
    $stmt = $pdo->query("SELECT id_producto, nombre, descripcion, precio_actual, stock FROM Producto WHERE stock > 0 ORDER BY nombre ASC");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los productos: " . $e->getMessage());
}

$cantidad_carrito = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

include '../includes/header.php';
?>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 60px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.8rem; margin-bottom: 10px;">Nuestra Tienda</h1>
    <p style="color: #886752; max-width: 600px; margin: 0 auto; font-size: 1.1rem; font-family: 'Poppins', sans-serif;">Llévate la relajación a casa. Productos exclusivos de LC Quiromasajes.</p>
</div>

<main style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="display: flex; justify-content: flex-end; margin-bottom: 30px;">
        <a href="carrito.php" style="background: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; color: #333; font-weight: 600; font-family: 'Poppins', sans-serif; box-shadow: 0 10px 20px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 10px; border: 1px solid #eee; transition: 0.3s;" onmouseover="this.style.borderColor='#EB6250'" onmouseout="this.style.borderColor='#eee'">
            🛍️ Mi Cesta
            <?php if ($cantidad_carrito > 0): ?>
                <span style="background: #EB6250; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.85rem;"><?= $cantidad_carrito ?></span>
            <?php endif; ?>
        </a>
    </div>

    <?php if ($mensaje_tienda): ?>
        <div style="background: #e6f4ea; color: #1e7e34; padding: 15px; border-radius: 12px; margin-bottom: 30px; text-align: center; font-family: 'Poppins', sans-serif; border: 1px solid #c3e6cb; animation: fadeIn 0.5s;">
            ✨ <?= htmlspecialchars($mensaje_tienda) ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        <?php foreach ($productos as $p): ?>
            <div style="background: #FFFFFF; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: 0.3s;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                
                <div style="padding: 30px; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; flex-grow: 1;">
                    <h3 style="margin: 0 0 10px 0; color: #2D2D2D; font-size: 1.3rem; font-family: 'Playfair Display', serif;">
                        <?= htmlspecialchars($p['nombre']) ?>
                    </h3>
                    
                    <p style="color: #777; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;">
                        <?= htmlspecialchars($p['descripcion']) ?>
                    </p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="font-size: 1.5rem; font-weight: 700; color: #EB6250;">
                            <?= number_format($p['precio_actual'], 2, ',', '.') ?> €
                        </span>
                        <span style="font-size: 0.8rem; color: #999;">Stock: <?= $p['stock'] ?></span>
                    </div>
                    
                    <form method="POST" action="index.php" style="margin: 0;">
                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                        <input type="hidden" name="precio" value="<?= $p['precio_actual'] ?>">
                        <input type="hidden" name="stock_max" value="<?= $p['stock'] ?>">
                        
                        <button type="submit" name="add_to_cart" style="background: #EB6250; color: white; border: none; padding: 14px 20px; border-radius: 50px; cursor: pointer; font-weight: 600; width: 100%; font-family: 'Poppins', sans-serif; transition: 0.2s;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
                            Añadir a la cesta
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>