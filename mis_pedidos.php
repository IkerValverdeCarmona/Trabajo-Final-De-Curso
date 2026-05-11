<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
try {
    $sql = "SELECT 
                id_producto,
                fecha_compra,
                cantidad,
                precio_unitario_venta,
                notas,
                (SELECT nombre FROM Producto WHERE id_producto = Opera.id_producto) AS nombre_producto
            FROM Opera 
            WHERE id_perfil = ? 
            ORDER BY fecha_compra DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $pedidos = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Error al cargar el historial de pedidos.");
}

include 'includes/header.php';
?>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 50px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin-bottom: 10px;">Mis Pedidos</h1>
    <p style="color: #886752; font-family: 'Poppins', sans-serif;">Historial de productos reservados en LC Quiromasajes</p>
</div>

<main style="max-width: 900px; margin: 40px auto; padding: 0 20px; font-family: 'Poppins', sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="perfil.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver a Mi Perfil</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div style="background: white; padding: 50px 20px; text-align: center; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <div style="font-size: 3rem; margin-bottom: 15px;">🛍️</div>
            <h3 style="color: #333; font-family: 'Playfair Display', serif;">Aún no has hecho ningún pedido</h3>
            <p style="color: #777; margin-bottom: 25px;">Descubre nuestros aceites y accesorios de bienestar.</p>
            <a href="tienda/index.php" style="background: #EB6250; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600;">Ir a la tienda</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach ($pedidos as $p): 
                $total_linea = $p['cantidad'] * $p['precio_unitario_venta'];
            ?>
                <div style="background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f9f9f9; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                    
                    <div style="flex: 1; min-width: 250px;">
                        <span style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px;">
                            Realizado el <?= date('d/m/Y', strtotime($p['fecha_compra'])) ?>
                        </span>
                        <h3 style="margin: 5px 0; color: #333; font-size: 1.2rem; font-family: 'Playfair Display', serif;">
                            <?= htmlspecialchars($p['nombre_producto']) ?>
                        </h3>
                        <p style="color: #666; margin: 0; font-size: 0.95rem;">
                            <?= $p['cantidad'] ?> unidad(es) x <?= number_format($p['precio_unitario_venta'], 2, ',', '.') ?> €
                        </p>
                        
                        <?php if(!empty($p['notas'])): ?>
                            <div style="margin-top: 10px; background: #FFF7EE; padding: 10px 15px; border-radius: 10px; font-size: 0.85rem; color: #886752; border-left: 3px solid #EB6250;">
                                📍 <?= htmlspecialchars($p['notas']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="text-align: right; min-width: 120px;">
                        <span style="display: block; font-size: 0.85rem; color: #888; margin-bottom: 5px;">Total a pagar</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: #EB6250;">
                            <?= number_format($total_linea, 2, ',', '.') ?> €
                        </span>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>