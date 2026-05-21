<?php
session_start();
require_once '../includes/db.php';

// Si el usuario no está logueado, lo mandamos al login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje_error'] = "Debes iniciar sesión para finalizar tu reserva de productos.";
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Quitar productos de la cesta
if (isset($_GET['quitar'])) {
    $id_q = $_GET['quitar'];
    unset($_SESSION['carrito'][$id_q]);
    header("Location: carrito.php");
    exit;
}

include '../includes/header.php';
?>

<div class="hero-publico">
    <h1>Tu Cesta de Bienestar</h1>
</div>

<main class="contenedor-tienda">
    <div style="margin-bottom: 20px;">
        <a href="index.php" class="enlace-volver">← Volver a la tienda</a>
    </div>

    <?php if (empty($_SESSION['carrito'])): ?>
        <div class="carrito-vacio">
            <div style="font-size: 3rem; margin-bottom: 15px;">🛒</div>
            <h3>Tu cesta está vacía</h3>
            <p>Aún no has seleccionado ningún producto.</p>
            <a href="index.php#productos" class="btn btn-primary">Descubrir productos</a>
        </div>
    <?php else: ?>
        <div class="layout-carrito">
            
            <div class="seccion-items-carrito">
                <h3 class="titulo-seccion">Resumen del Pedido</h3>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <div class="item-carrito">
                        <div>
                            <h4 class="item-nombre"><?= htmlspecialchars($item['nombre']) ?></h4>
                            <small class="item-detalles"><?= $item['cantidad'] ?> unidad(es) x <?= number_format($item['precio'], 2, ',', '.') ?> €</small>
                        </div>
                        <div style="text-align: right;">
                            <div class="item-precio-total"><?= number_format($subtotal, 2, ',', '.') ?> €</div>
                            <a href="carrito.php?quitar=<?= $id ?>" class="enlace-quitar">Quitar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="carrito-total-final">
                    Total: <?= number_format($total, 2, ',', '.') ?> €
                </div>
            </div>

            <div class="seccion-checkout">
                <h3 class="titulo-seccion" style="border-bottom: none; padding-bottom: 0;">Recogida en Centro</h3>
                <p class="checkout-desc">Tu pedido se preparará y podrás pagarlo al recogerlo en Avenida María Guerrero.</p>
                
                <form action="procesar_reserva.php" method="POST" style="padding: 0; box-shadow: none; background: transparent; width: 100%;">
                    <div class="grupo-entrada">
                        <label>Día de recogida:</label>
                        <input type="date" name="fecha_recogida" required min="<?= date('Y-m-d') ?>" class="input-control">
                    </div>
                    <div class="grupo-entrada">
                        <label>Tramo horario:</label>
                        <select name="hora_recogida" required class="input-control">
                            <option value="Mañana">Turno Mañana (10:00 - 13:30)</option>
                            <option value="Tarde">Turno Tarde (17:00 - 20:30)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                        Confirmar Reserva
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>