<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
try {
    $sql = "SELECT id_producto, fecha_compra, cantidad, precio_unitario_venta,
                   (SELECT nombre FROM Producto WHERE id_producto = Opera.id_producto) AS nombre_producto
            FROM Opera WHERE id_perfil = ? ORDER BY fecha_compra DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $pedidos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error crítico en la base de datos: " . $e->getMessage());
}

include 'includes/header.php';
?>

<div class="hero-seccion">
    <h1>Mis Pedidos</h1>
    <p>Historial de productos reservados en LC Quiromasajes</p>
</div>

<main class="contenedor-pedidos">
    <div style="margin-bottom: 20px;">
        <a href="perfil.php" class="enlace-volver">← Volver a Mi Perfil</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="tarjeta-vacia">
            <div class="tarjeta-vacia-icono">🛍️</div>
            <h3>Aún no has hecho ningún pedido</h3>
            <p style="color: #777; margin-bottom: 25px;">Descubre nuestros aceites y accesorios de bienestar.</p>
            <a href="tienda/index.php" class="btn btn-primary">Ir a la tienda</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach ($pedidos as $p): 
                $total_linea = $p['cantidad'] * $p['precio_unitario_venta'];
            ?>
                <div class="tarjeta-pedido">
                    <div style="flex: 1; min-width: 250px;">
                        <span style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px;">
                            Realizado el <?= date('d/m/Y', strtotime($p['fecha_compra'])) ?>
                        </span>
                        <h3 style="margin: 5px 0; color: var(--color-text-dark); font-size: 1.2rem; font-family: var(--font-title);">
                            <?= htmlspecialchars($p['nombre_producto']) ?>
                        </h3>
                        <p style="color: var(--color-text-muted); margin: 0; font-size: 0.95rem;">
                            <?= $p['cantidad'] ?> unidad(es) x <?= number_format($p['precio_unitario_venta'], 2, ',', '.') ?> €
                        </p>
                        
                        <?php if(!empty($p['notas'])): ?>
                            <div class="estado-nota">📍 <?= htmlspecialchars($p['notas']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div style="text-align: right; min-width: 120px;">
                        <span style="display: block; font-size: 0.85rem; color: #888; margin-bottom: 5px;">Total a pagar</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">
                            <?= number_format($total_linea, 2, ',', '.') ?> €
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>