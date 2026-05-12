<?php
session_start();
require_once '../includes/db.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Lógica para añadir PRODUCTOS al carrito
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock_max = $_POST['stock_max'];
    
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
    // 1. Obtenemos los Productos (que tengan stock)
    $stmt_prod = $pdo->query("SELECT id_producto, nombre, descripcion, precio_actual, stock FROM Producto WHERE stock > 0 ORDER BY nombre ASC");
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

    // 2. Obtenemos los Servicios/Tratamientos (que estén activos)
    $stmt_serv = $pdo->query("SELECT id_servicio, nombre, descripcion, precio_actual, duracion_minutos FROM Servicios WHERE activo = 1 ORDER BY nombre ASC");
    $servicios = $stmt_serv->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al cargar el catálogo: " . $e->getMessage());
}

$cantidad_carrito = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

include '../includes/header.php';
?>

<div class="hero-publico">
    <h1>Bienestar a tu Medida</h1>
    <p>Descubre nuestros productos exclusivos y reserva tus tratamientos favoritos en un solo lugar.</p>
</div>

<main class="contenedor-tienda">
    
    <div class="acciones-tienda">
        <a href="carrito.php" class="btn-carrito-flotante">
            🛍️ Mi Cesta
            <?php if ($cantidad_carrito > 0): ?>
                <span class="badge-cantidad"><?= $cantidad_carrito ?></span>
            <?php endif; ?>
        </a>
    </div>

    <?php if ($mensaje_tienda): ?>
        <div class="alerta-exito" style="text-align: center; margin-bottom: 30px;">
            ✨ <?= htmlspecialchars($mensaje_tienda) ?>
        </div>
    <?php endif; ?>

    <div class="grid-productos">
        
        <?php foreach ($servicios as $s): ?>
            <div class="tarjeta-producto" style="border: 1px solid #E8F5E9;">
                <div class="producto-cuerpo">
                    <div style="margin-bottom: 15px;">
                        <span class="etiqueta-servicio" style="background: #E8F5E9; color: #2E7D32; font-weight: 600;">✨ Tratamiento</span>
                    </div>
                    
                    <h3 class="producto-titulo"><?= htmlspecialchars($s['nombre']) ?></h3>
                    <p class="producto-desc"><?= htmlspecialchars($s['descripcion']) ?></p>
                    
                    <div class="producto-fila-precio">
                        <span class="producto-precio"><?= number_format($s['precio_actual'], 2, ',', '.') ?> €</span>
                        <span class="producto-stock">⏱ <?= $s['duracion_minutos'] ?> min</span>
                    </div>
                    
                    <a href="../reservar.php" class="btn btn-outline-primary" style="width: 100%;">
                        Reservar Cita
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php foreach ($productos as $p): ?>
            <div class="tarjeta-producto" style="border: 1px solid #FFF7EE;">
                <div class="producto-cuerpo">
                    <div style="margin-bottom: 15px;">
                        <span class="etiqueta-servicio" style="background: #FFF7EE; color: #EB6250; font-weight: 600;">🧴 Producto físico</span>
                    </div>

                    <h3 class="producto-titulo"><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p class="producto-desc"><?= htmlspecialchars($p['descripcion']) ?></p>
                    
                    <div class="producto-fila-precio">
                        <span class="producto-precio"><?= number_format($p['precio_actual'], 2, ',', '.') ?> €</span>
                        <span class="producto-stock">Stock: <?= $p['stock'] ?></span>
                    </div>
                    
                    <form method="POST" action="index.php" style="margin: 0; padding: 0; box-shadow: none; background: transparent;">
                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                        <input type="hidden" name="precio" value="<?= $p['precio_actual'] ?>">
                        <input type="hidden" name="stock_max" value="<?= $p['stock'] ?>">
                        
                        <button type="submit" name="add_to_cart" class="btn btn-primary" style="width: 100%;">
                            Añadir a la cesta
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</main>

<?php include '../includes/footer.php'; ?><?php
session_start();
require_once '../includes/db.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Lógica para añadir PRODUCTOS al carrito
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock_max = $_POST['stock_max'];
    
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
    // 1. Obtenemos los Productos (que tengan stock)
    $stmt_prod = $pdo->query("SELECT id_producto, nombre, descripcion, precio_actual, stock FROM Producto WHERE stock > 0 ORDER BY nombre ASC");
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

    // 2. Obtenemos los Servicios/Tratamientos (que estén activos)
    $stmt_serv = $pdo->query("SELECT id_servicio, nombre, descripcion, precio_actual, duracion_minutos FROM Servicios WHERE activo = 1 ORDER BY nombre ASC");
    $servicios = $stmt_serv->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al cargar el catálogo: " . $e->getMessage());
}

$cantidad_carrito = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

include '../includes/header.php';
?>

<div class="hero-publico">
    <h1>Bienestar a tu Medida</h1>
    <p>Descubre nuestros productos exclusivos y reserva tus tratamientos favoritos en un solo lugar.</p>
</div>

<main class="contenedor-tienda">
    
    <div class="acciones-tienda">
        <a href="carrito.php" class="btn-carrito-flotante">
            🛍️ Mi Cesta
            <?php if ($cantidad_carrito > 0): ?>
                <span class="badge-cantidad"><?= $cantidad_carrito ?></span>
            <?php endif; ?>
        </a>
    </div>

    <?php if ($mensaje_tienda): ?>
        <div class="alerta-exito" style="text-align: center; margin-bottom: 30px;">
            ✨ <?= htmlspecialchars($mensaje_tienda) ?>
        </div>
    <?php endif; ?>

    <div class="grid-productos">
        
        <?php foreach ($servicios as $s): ?>
            <div class="tarjeta-producto" style="border: 1px solid #E8F5E9;">
                <div class="producto-cuerpo">
                    <div style="margin-bottom: 15px;">
                        <span class="etiqueta-servicio" style="background: #E8F5E9; color: #2E7D32; font-weight: 600;">✨ Tratamiento</span>
                    </div>
                    
                    <h3 class="producto-titulo"><?= htmlspecialchars($s['nombre']) ?></h3>
                    <p class="producto-desc"><?= htmlspecialchars($s['descripcion']) ?></p>
                    
                    <div class="producto-fila-precio">
                        <span class="producto-precio"><?= number_format($s['precio_actual'], 2, ',', '.') ?> €</span>
                        <span class="producto-stock">⏱ <?= $s['duracion_minutos'] ?> min</span>
                    </div>
                    
                    <a href="../reservar.php" class="btn btn-outline-primary" style="width: 100%;">
                        Reservar Cita
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php foreach ($productos as $p): ?>
            <div class="tarjeta-producto" style="border: 1px solid #FFF7EE;">
                <div class="producto-cuerpo">
                    <div style="margin-bottom: 15px;">
                        <span class="etiqueta-servicio" style="background: #FFF7EE; color: #EB6250; font-weight: 600;">🧴 Producto físico</span>
                    </div>

                    <h3 class="producto-titulo"><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p class="producto-desc"><?= htmlspecialchars($p['descripcion']) ?></p>
                    
                    <div class="producto-fila-precio">
                        <span class="producto-precio"><?= number_format($p['precio_actual'], 2, ',', '.') ?> €</span>
                        <span class="producto-stock">Stock: <?= $p['stock'] ?></span>
                    </div>
                    
                    <form method="POST" action="index.php" style="margin: 0; padding: 0; box-shadow: none; background: transparent;">
                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                        <input type="hidden" name="precio" value="<?= $p['precio_actual'] ?>">
                        <input type="hidden" name="stock_max" value="<?= $p['stock'] ?>">
                        
                        <button type="submit" name="add_to_cart" class="btn btn-primary" style="width: 100%;">
                            Añadir a la cesta
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</main>

<?php include '../includes/footer.php'; ?>