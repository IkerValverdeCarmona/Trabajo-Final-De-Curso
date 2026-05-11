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

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 40px 20px; text-align: center;">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.2rem; margin: 0;">Tu Cesta de Bienestar</h1>
</div>

<main style="max-width: 1000px; margin: 40px auto; padding: 0 20px; font-family: 'Poppins', sans-serif;">
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver a la tienda</a>
    </div>

    <?php if (empty($_SESSION['carrito'])): ?>
        <div style="text-align: center; padding: 60px 20px; background: #FFFFFF; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
            <div style="font-size: 3rem; margin-bottom: 15px;">🛒</div>
            <h3 style="color: #333; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Tu cesta está vacía</h3>
            <p style="color: #777; margin-bottom: 25px;">Aún no has seleccionado ningún producto.</p>
            <a href="index.php" style="background: #EB6250; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600;">Descubrir productos</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
            
            <div style="flex: 1; min-width: 300px; background: #FFFFFF; border-radius: 20px; padding: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: #2D2D2D; font-family: 'Playfair Display', serif; border-bottom: 2px solid #FFF7EE; padding-bottom: 15px;">Resumen del Pedido</h3>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: 15px 0;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 1.1rem;"><?= htmlspecialchars($item['nombre']) ?></h4>
                            <small style="color: #888;"><?= $item['cantidad'] ?> unidad(es) x <?= number_format($item['precio'], 2, ',', '.') ?> €</small>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #333; font-size: 1.1rem; margin-bottom: 5px;"><?= number_format($subtotal, 2, ',', '.') ?> €</div>
                            <a href="carrito.php?quitar=<?= $id ?>" style="color: #c5221f; font-size: 0.8rem; text-decoration: underline;">Quitar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="text-align: right; margin-top: 25px; font-size: 1.4rem; font-weight: 700; color: #EB6250;">
                    Total: <?= number_format($total, 2, ',', '.') ?> €
                </div>
            </div>

            <div style="flex: 0 1 350px; background: #FFFFFF; border-radius: 20px; padding: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); border: 1px solid #FFF7EE;">
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-top: 0; margin-bottom: 20px; color: #333;">Recogida en Centro</h3>
                <p style="font-size: 0.85rem; color: #666; margin-bottom: 20px; line-height: 1.5;">Tu pedido se preparará y podrás pagarlo al recogerlo en Avenida María Guerrero.</p>
                
                <form action="procesar_reserva.php" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                    <div>
                        <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Día de recogida:</label>
                        <input type="date" name="fecha_recogida" required min="<?= date('Y-m-d') ?>" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                    </div>
                    <div>
                        <label style="font-size: 0.9rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">Tramo horario:</label>
                        <select name="hora_recogida" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                            <option value="Mañana">Turno Mañana (10:00 - 13:30)</option>
                            <option value="Tarde">Turno Tarde (17:00 - 20:30)</option>
                        </select>
                    </div>
                    
                    <button type="submit" style="background: #EB6250; color: white; border: none; padding: 16px; border-radius: 50px; font-weight: 600; cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif; margin-top: 10px;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
                        Confirmar Reserva
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>