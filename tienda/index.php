<?php
session_start();
 include '../includes/header.php';
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
    $cantidad_añadida = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
    
    if (isset($_SESSION['carrito'][$id])) {
        $nueva_cantidad = $_SESSION['carrito'][$id]['cantidad'] + $cantidad_añadida;
        if ($nueva_cantidad <= $stock_max) {
            $_SESSION['carrito'][$id]['cantidad'] = $nueva_cantidad;
            $_SESSION['mensaje_tienda'] = "¡Has añadido más unidades de $nombre!";
        } else {
            $_SESSION['mensaje_tienda'] = "No hay suficiente stock disponible de $nombre para añadir esa cantidad.";
        }
    } else {
        if ($cantidad_añadida <= $stock_max) {
            $_SESSION['carrito'][$id] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad_añadida
            ];
            $_SESSION['mensaje_tienda'] = "¡$nombre añadido a tu cesta!";
        } else {
            $_SESSION['mensaje_tienda'] = "No hay suficiente stock de $nombre.";
        }
    }
    header("Location: index.php#productos"); // Redirigir de vuelta a la sección de productos
    exit;
}

$mensaje_tienda = "";
if (isset($_SESSION['mensaje_tienda'])) {
    $mensaje_tienda = $_SESSION['mensaje_tienda'];
    unset($_SESSION['mensaje_tienda']);
}

try {
    // 1. Obtenemos los Productos
    $stmt_prod = $pdo->query("SELECT id_producto, nombre, descripcion, precio_actual, stock, imagen FROM Producto WHERE stock > 0 ORDER BY nombre ASC");
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

    // 2. Obtenemos los Servicios/Tratamientos
    $stmt_serv = $pdo->query("SELECT id_servicio, nombre, descripcion, precio_actual, duracion_minutos FROM Servicios WHERE activo = 1 ORDER BY nombre ASC");
    $servicios = $stmt_serv->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al cargar el catálogo: " . $e->getMessage());
}

$cantidad_carrito = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
?>

<style>
    .btn-outline-marca {
        color: #EB6250;
        border: 1px solid #EB6250;
        background-color: transparent;
        transition: all 0.3s ease;
    }
    .btn-outline-marca:hover {
        background-color: #EB6250;
        color: #FFFFFF;
    }
</style>

<div class="hero-publico">
    <h1>Bienestar a tu Medida</h1>
    <p>Descubre nuestros productos exclusivos y reserva tus tratamientos favoritos.</p>
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
        <div class="alerta-exito" style="text-align: center; margin-bottom: 30px; background-color: #dcfce7; color: #166534; padding: 15px; border-radius: 8px;">
            ✨ <?= htmlspecialchars($mensaje_tienda) ?>
        </div>
    <?php endif; ?>

    <div id="tratamientos" style="margin-top: 20px; margin-bottom: 60px;">
        <h2 style="font-family: var(--font-title, 'Playfair Display'); color: #EB6250; font-size: 2.2rem; border-bottom: 2px solid #FDF2D8; padding-bottom: 15px; margin-bottom: 30px;">
            💆‍♀️ Terapias y Masajes
        </h2>
        
        <div class="grid-productos">
            <?php foreach ($servicios as $s): ?>
                <div class="tarjeta-producto" style="border: 1px solid #FDF2D8; border-radius: 20px; padding: 20px;">
                    <div class="producto-cuerpo">
                        <div style="margin-bottom: 15px;">
                            <span class="etiqueta-servicio" style="background: #FFF7EE; color: #EB6250; font-weight: 600; padding: 5px 10px; border-radius: 10px; font-size: 0.9rem;">✨ Tratamiento</span>
                        </div>
                        
                        <h3 class="producto-titulo" style="font-family: 'Playfair Display', serif;"><?= htmlspecialchars($s['nombre']) ?></h3>
                        <p class="producto-desc"><?= htmlspecialchars($s['descripcion']) ?></p>
                        
                        <div class="producto-fila-precio" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <span class="producto-precio fw-bold fs-5" style="color: #EB6250;"><?= number_format($s['precio_actual'], 2, ',', '.') ?> €</span>
                            <span class="producto-stock text-muted">⏱ <?= $s['duracion_minutos'] ?> min</span>
                        </div>
                        
                        <a href="../public/reservar.php" class="btn btn-outline-marca rounded-pill" style="width: 100%; display: block; text-align: center; padding: 10px; text-decoration: none;">
                            Reservar Cita
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="productos" style="margin-bottom: 60px;">
        <h2 style="font-family: var(--font-title, 'Playfair Display'); color: #EB6250; font-size: 2.2rem; border-bottom: 2px solid #FDF2D8; padding-bottom: 15px; margin-bottom: 30px;">
            🛍️ Tienda de Productos
        </h2>

        <div class="row">
            <?php foreach ($productos as $p): ?>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm" style="border-radius: 20px; border: none; cursor: pointer;" 
                         data-bs-toggle="modal" data-bs-target="#modalProducto<?= $p['id_producto'] ?>">
                        
                        <img src="../assets/img/productos/<?= htmlspecialchars($p['imagen'] ?? 'default.jpg') ?>" 
                             class="card-img-top" style="border-radius: 20px 20px 0 0; height: 200px; object-fit: cover;" 
                             alt="<?= htmlspecialchars($p['nombre']) ?>">
                        
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title" style="font-family: 'Playfair Display', serif;"><?= htmlspecialchars($p['nombre']) ?></h5>
                                <p class="fw-bold fs-5" style="color: #EB6250;"><?= number_format($p['precio_actual'], 2) ?> €</p>
                            </div>
                            <button type="button" class="btn btn-outline-marca rounded-pill w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modalProducto<?= $p['id_producto'] ?>">Ver detalles</button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalProducto<?= $p['id_producto'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                            <div class="modal-body p-0">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10; background-color: white; border-radius: 50%; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></button>
                                
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <img src="../assets/img/productos/<?= htmlspecialchars($p['imagen'] ?? 'default.jpg') ?>" 
                                             class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 300px;" 
                                             alt="<?= htmlspecialchars($p['nombre']) ?>">
                                    </div>
                                    
                                    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                                        <h2 class="mb-3" style="font-family: 'Playfair Display', serif; color: #333;"><?= htmlspecialchars($p['nombre']) ?></h2>
                                        <h3 class="mb-4 fw-bold" style="color: #EB6250;"><?= number_format($p['precio_actual'], 2) ?> €</h3>
                                        
                                        <p class="text-muted mb-4" style="line-height: 1.6;">
                                            <?= htmlspecialchars($p['descripcion'] ?? 'Descripción no disponible.') ?>
                                        </p>
                                        
                                        <form action="index.php" method="POST">
                                            <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                            <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                                            <input type="hidden" name="precio" value="<?= $p['precio_actual'] ?>">
                                            <input type="hidden" name="stock_max" value="<?= $p['stock'] ?>">
                                            
                                            <div class="d-flex gap-3 align-items-center mb-4">
                                                <label class="fw-bold" style="color: #555;">Cantidad:</label>
                                                <input type="number" name="cantidad" value="1" min="1" max="<?= $p['stock'] ?? 10 ?>" class="form-control text-center" style="width: 80px; border-radius: 12px; border: 1px solid #ddd;">
                                            </div>
                                            
                                            <button type="submit" name="add_to_cart" class="btn w-100 rounded-pill py-3" style="background-color: #EB6250; color: white; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(235, 98, 80, 0.3); transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#D75443'" onmouseout="this.style.backgroundColor='#EB6250'">
                                                🛒 Añadir al carrito
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include '../includes/footer.php'; ?>